DROP VIEW IF EXISTS view_detail_penjualan;
CREATE VIEW view_detail_penjualan AS 
SELECT dp.id AS id_detail_penjualan, m.nama_menu, dp.jumlah, dp.subtotal 
FROM detail_penjualans dp 
JOIN menus m ON dp.menu_id = m.id;

DROP VIEW IF EXISTS view_penjualan;
CREATE VIEW view_penjualan AS 
SELECT p.id AS id_penjualan, p.waktu_pemesanan AS tanggal_penjualan, 'Admin' AS nama_pegawai, p.total_penjualan 
FROM penjualans p;

DROP VIEW IF EXISTS view_pembelian;
CREATE VIEW view_pembelian AS 
SELECT pb.id AS id_pembelian, pb.tanggal_pembelian, s.nama_supplier, pg.nama_pegawai, pb.total_pembelian 
FROM pembelians pb 
LEFT JOIN suppliers s ON pb.supplier_id = s.id 
LEFT JOIN pegawais pg ON pb.pegawai_id = pg.id;

DROP VIEW IF EXISTS view_stok_bahan;
CREATE VIEW view_stok_bahan AS 
SELECT id AS id_bahan, nama_bahan, satuan, stok 
FROM bahan_bakus;

DROP VIEW IF EXISTS view_laporan_penjualan;
CREATE VIEW view_laporan_penjualan AS 
SELECT DATE(waktu_pemesanan) AS tanggal_penjualan, count(id) AS jumlah_transaksi, sum(total_penjualan) AS total_pendapatan 
FROM penjualans 
GROUP BY DATE(waktu_pemesanan);
