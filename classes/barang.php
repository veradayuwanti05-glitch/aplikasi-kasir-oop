<?php

require_once 'induk.php';

class Barang extends IndukData
{
    private $koneksi;

    public function __construct($db)
    {
        $this->koneksi = $db;
    }

    public function getKategori()
    {
        $q = $this->koneksi->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
        return $q->fetch_all(MYSQLI_ASSOC);
    }

    public function getSupplier()
    {
        $q = $this->koneksi->query("SELECT * FROM supplier ORDER BY nama_supplier ASC");
        return $q->fetch_all(MYSQLI_ASSOC);
    }

    public function ambilSemua()
    {
        $sql = "SELECT barang.*, kategori.nama_kategori, supplier.nama_supplier, supplier.kota
                FROM barang
                JOIN kategori ON barang.kategori_id = kategori.id
                JOIN supplier ON barang.supplier_id = supplier.id
                ORDER BY barang.id DESC";
        $q = $this->koneksi->query($sql);
        $data = [];

        while ($d = $q->fetch_assoc()) {
            $d['total_nilai'] = $this->hitungTotal($d['harga'], $d['stok']);
            $data[] = $d;
        }

        return $data;
    }

    public function ambilById($id)
    {
        $id = (int)$id;
        $q = $this->koneksi->query("SELECT * FROM barang WHERE id = $id");
        return $q->fetch_assoc();
    }

    public function simpan($kat_id, $sup_id, $nama, $harga, $stok)
    {
        $kat_id = (int)$kat_id;
        $sup_id = (int)$sup_id;
        $nama = $this->koneksi->real_escape_string($nama);
        $harga = (int)$harga;
        $stok = (int)$stok;

        return $this->koneksi->query("INSERT INTO barang (kategori_id, supplier_id, nama_barang, harga, stok) VALUES ($kat_id, $sup_id, '$nama', $harga, $stok)");
    }

    public function ubah($id, $kat_id, $sup_id, $nama, $harga, $stok)
    {
        $id = (int)$id;
        $kat_id = (int)$kat_id;
        $sup_id = (int)$sup_id;
        $nama = $this->koneksi->real_escape_string($nama);
        $harga = (int)$harga;
        $stok = (int)$stok;

        return $this->koneksi->query("UPDATE barang SET kategori_id=$kat_id, supplier_id=$sup_id, nama_barang='$nama', harga=$harga, stok=$stok WHERE id=$id");
    }

    public function hapus($id)
    {
        $id = (int)$id;
        return $this->koneksi->query("DELETE FROM barang WHERE id = $id");
    }
}