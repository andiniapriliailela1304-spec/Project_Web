<?php
/**
 * ruangan_helper.php
 *
 * Kumpulan fungsi bantu untuk menghitung & menyinkronkan status ruangan
 * berdasarkan jadwal booking yang sedang aktif SEKARANG (bukan cuma
 * berdasarkan "ada booking Disetujui atau tidak").
 *
 * Dipakai bersama di:
 *   - admin/ruangan.php          (saat admin membuka daftar ruangan)
 *   - admin/setujui_booking.php  (saat booking di-approve)
 *   - admin/tolak_booking.php    (saat booking ditolak)
 *
 * Kenapa perlu satu fungsi bersama:
 * Status ruangan ("Tersedia" / "Booking") seharusnya mencerminkan APAKAH
 * ADA BOOKING DISETUJUI YANG SEDANG BERLANGSUNG SAAT INI, bukan sekadar
 * "ada booking disetujui" (yang bisa saja untuk besok/minggu depan).
 * Kalau logika ini ditulis ulang di 3 tempat, gampang tidak sinkron kalau
 * salah satu lupa diupdate.
 */

/**
 * Menghitung & menyimpan status ruangan yang seharusnya, berdasarkan
 * apakah ada booking berstatus 'Disetujui' yang jam bookingnya mencakup
 * waktu SEKARANG.
 *
 * @param mysqli $conn
 * @param int    $id_ruangan
 * @return string status ruangan terbaru ('Tersedia' atau 'Booking')
 */
function sinkronisasi_status_ruangan(mysqli $conn, int $id_ruangan): string
{
    $tanggal_sekarang = date('Y-m-d');
    $jam_sekarang      = date('H:i:s');

    // Cari booking DISETUJUI di ruangan ini yang:
    // - tanggalnya hari ini, DAN
    // - jam_mulai <= sekarang < jam_selesai
    $stmt = mysqli_prepare($conn, "
        SELECT id_booking
        FROM booking
        WHERE id_ruangan = ?
          AND status_booking = 'Disetujui'
          AND tanggal = ?
          AND jam_mulai <= ?
          AND jam_selesai > ?
        LIMIT 1
    ");
    mysqli_stmt_bind_param(
        $stmt,
        "isss",
        $id_ruangan,
        $tanggal_sekarang,
        $jam_sekarang,
        $jam_sekarang
    );
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
    $sedang_dipakai = mysqli_num_rows($hasil) > 0;
    mysqli_stmt_close($stmt);

    $status_baru = $sedang_dipakai ? 'Booking' : 'Tersedia';

    $stmtUpdate = mysqli_prepare($conn, "
        UPDATE ruangan
        SET status = ?
        WHERE id_ruangan = ?
    ");
    mysqli_stmt_bind_param($stmtUpdate, "si", $status_baru, $id_ruangan);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);

    return $status_baru;
}

/**
 * Menjalankan sinkronisasi_status_ruangan() untuk SEMUA ruangan sekaligus.
 * Cocok dipakai di ruangan.php supaya semua baris status ter-update
 * setiap kali halaman itu dibuka.
 *
 * @param mysqli $conn
 * @return void
 */
function sinkronisasi_semua_ruangan(mysqli $conn): void
{
    $hasil = mysqli_query($conn, "SELECT id_ruangan FROM ruangan");
    while ($row = mysqli_fetch_assoc($hasil)) {
        sinkronisasi_status_ruangan($conn, (int) $row['id_ruangan']);
    }
}

/**
 * Mengecek apakah ada booking lain yang BENTROK JAM dengan booking yang
 * mau di-approve, di mana booking lain itu SUDAH berstatus 'Disetujui'.
 *
 * Dipakai di setujui_booking.php SEBELUM approve, supaya admin tidak bisa
 * approve dua booking yang jam & ruangannya tumpang tindih.
 *
 * @param mysqli $conn
 * @param int    $id_ruangan
 * @param string $tanggal              format Y-m-d
 * @param string $jam_mulai            format H:i:s
 * @param string $jam_selesai          format H:i:s
 * @param int    $id_booking_exclude   id booking yang sedang diproses (dikecualikan dari pengecekan)
 * @return array|null  data booking yang bentrok, atau null kalau tidak ada
 */
function cek_bentrok_disetujui(
    mysqli $conn,
    int $id_ruangan,
    string $tanggal,
    string $jam_mulai,
    string $jam_selesai,
    int $id_booking_exclude
): ?array {
    $stmt = mysqli_prepare($conn, "
        SELECT id_booking, tanggal, jam_mulai, jam_selesai
        FROM booking
        WHERE id_ruangan = ?
          AND tanggal = ?
          AND status_booking = 'Disetujui'
          AND id_booking != ?
          AND jam_mulai < ?
          AND jam_selesai > ?
        LIMIT 1
    ");
    mysqli_stmt_bind_param(
        $stmt,
        "isiss",
        $id_ruangan,
        $tanggal,
        $id_booking_exclude,
        $jam_selesai,
        $jam_mulai
    );
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($hasil);
    mysqli_stmt_close($stmt);

    return $row ?: null;
}