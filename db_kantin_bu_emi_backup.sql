-- ==========================================
-- DATABASE KANTIN MAKAN BU EMI
-- ==========================================

DROP DATABASE IF EXISTS db_kantin_bu_emi;
CREATE DATABASE db_kantin_bu_emi;
USE db_kantin_bu_emi;

CREATE TABLE pegawai (
    id_pegawai INT AUTO_INCREMENT PRIMARY KEY,
    nama_pegawai VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50) NOT NULL,
    no_hp VARCHAR(15)
);
CREATE TABLE menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL
);
CREATE TABLE supplier (
    id_supplier INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL,
    alamat VARCHAR(150),
    no_hp VARCHAR(15)
);
CREATE TABLE bahan_baku (
    id_bahan INT AUTO_INCREMENT PRIMARY KEY,
    nama_bahan VARCHAR(100) NOT NULL,
    satuan VARCHAR(20),
    stok INT NOT NULL
);
CREATE TABLE pembelian (
    id_pembelian INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_pembelian DATE NOT NULL,
    total_pembelian DECIMAL(12,2) DEFAULT 0,
    id_supplier INT NOT NULL,
    id_pegawai INT NOT NULL,
    FOREIGN KEY (id_supplier) REFERENCES supplier(id_supplier),
    FOREIGN KEY (id_pegawai) REFERENCES pegawai(id_pegawai)
);
CREATE TABLE detail_pembelian (
    id_detail_pembelian INT AUTO_INCREMENT PRIMARY KEY,
    id_pembelian INT NOT NULL,
    id_bahan INT NOT NULL,
    jumlah INT NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (id_pembelian) REFERENCES pembelian(id_pembelian),
    FOREIGN KEY (id_bahan) REFERENCES bahan_baku(id_bahan)
);
CREATE TABLE penjualan (
    id_penjualan INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_penjualan DATE NOT NULL,
    total_penjualan DECIMAL(12,2) DEFAULT 0,
    id_pegawai INT NOT NULL,
    FOREIGN KEY (id_pegawai) REFERENCES pegawai(id_pegawai)
);
CREATE TABLE detail_penjualan (
    id_detail_penjualan INT AUTO_INCREMENT PRIMARY KEY,
    id_penjualan INT NOT NULL,
    id_menu INT NOT NULL,
    jumlah INT NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (id_penjualan) REFERENCES penjualan(id_penjualan),
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
);
CREATE TABLE laporan (
    id_laporan INT AUTO_INCREMENT PRIMARY KEY,
    periode VARCHAR(20),
    total_penjualan DECIMAL(12,2),
    total_pengeluaran DECIMAL(12,2),
    laba DECIMAL(12,2)
);

INSERT INTO pegawai (nama_pegawai,jabatan,no_hp) VALUES
('Bu Emi','Pemilik','081234567890'),
('Andi','Kasir','081234567891'),
('Siti','Koki','081234567892');

INSERT INTO menu (nama_menu,harga) VALUES
('Ayam Geprek',10000);

INSERT INTO supplier (nama_supplier,alamat,no_hp) VALUES
('Pasar Impres','Lhokseumawe','082111111111'),
('Pasar Batuphat','Lhokseumawe','082222222222');

INSERT INTO bahan_baku (nama_bahan,satuan,stok) VALUES
('Ayam','Kg',50),
('Beras','Kg',100),
('Cabai','Kg',20),
('Minyak Goreng','Liter',30),
('Bawang Putih','Kg',15),
('Bawang Merah','Kg',15),
('Garam','Kg',10),
('Penyedap','Pack',20),
('Tepung','Kg',25),
('Kubis','Kg',20);

INSERT INTO pembelian (tanggal_pembelian,total_pembelian,id_supplier,id_pegawai) VALUES
('2025-06-01',0,1,1),
('2025-06-03',0,1,2),
('2025-06-05',0,1,1),
('2025-06-07',0,2,3),
('2025-06-09',0,1,2),
('2025-06-11',0,1,1),
('2025-06-13',0,2,3),
('2025-06-15',0,1,2),
('2025-06-17',0,1,1),
('2025-06-19',0,2,3);

INSERT INTO detail_pembelian
(id_pembelian,id_bahan,jumlah,harga,subtotal)
VALUES

(1,1,10,35000,350000),
(1,3,5,40000,200000),

(2,2,20,15000,300000),
(2,4,10,18000,180000),

(3,5,5,30000,150000),
(3,6,5,28000,140000),

(4,1,8,35000,280000),
(4,9,10,12000,120000),

(5,10,15,8000,120000),
(5,7,5,10000,50000),

(6,8,10,5000,50000),
(6,3,4,40000,160000),

(7,1,12,35000,420000),
(7,2,25,15000,375000),

(8,4,8,18000,144000),
(8,5,6,30000,180000),

(9,6,6,28000,168000),
(9,9,12,12000,144000),

(10,10,20,8000,160000),
(10,7,8,10000,80000);

INSERT INTO penjualan (tanggal_penjualan,total_penjualan,id_pegawai) VALUES
('2025-06-01',0,2),
('2025-06-02',0,2),
('2025-06-03',0,3),
('2025-06-04',0,2),
('2025-06-05',0,3),
('2025-06-06',0,2),
('2025-06-07',0,3),
('2025-06-08',0,2),
('2025-06-09',0,3),
('2025-06-10',0,2),
('2025-06-11',0,3),
('2025-06-12',0,2),
('2025-06-13',0,3),
('2025-06-14',0,2),
('2025-06-15',0,3),
('2025-06-16',0,2),
('2025-06-17',0,3),
('2025-06-18',0,2),
('2025-06-19',0,3),
('2025-06-20',0,2);

INSERT INTO detail_penjualan
(id_penjualan,id_menu,jumlah,subtotal)
VALUES

(1,1,2,20000),
(2,1,3,30000),
(3,1,1,10000),
(4,1,4,40000),
(5,1,2,20000),

(6,1,5,50000),
(7,1,3,30000),
(8,1,2,20000),
(9,1,4,40000),
(10,1,1,10000),

(11,1,5,50000),
(12,1,3,30000),
(13,1,2,20000),
(14,1,4,40000),
(15,1,2,20000),

(16,1,3,30000),
(17,1,1,10000),
(18,1,5,50000),
(19,1,2,20000),
(20,1,4,40000);

INSERT INTO laporan
(periode,total_penjualan,total_pengeluaran,laba)
VALUES
('Juni 2025',600000,4121000,-3521000),
('Juli 2025',0,0,0);

CREATE VIEW view_penjualan AS
SELECT
    p.id_penjualan,
    p.tanggal_penjualan,
    pg.nama_pegawai,
    p.total_penjualan
FROM penjualan p
JOIN pegawai pg
ON p.id_pegawai = pg.id_pegawai;

CREATE VIEW view_detail_penjualan AS
SELECT
    dp.id_detail_penjualan,
    m.nama_menu,
    dp.jumlah,
    dp.subtotal
FROM detail_penjualan dp
JOIN menu m
ON dp.id_menu = m.id_menu;

CREATE VIEW view_pembelian AS
SELECT
    pb.id_pembelian,
    pb.tanggal_pembelian,
    s.nama_supplier,
    pg.nama_pegawai,
    pb.total_pembelian
FROM pembelian pb
JOIN supplier s
ON pb.id_supplier = s.id_supplier
JOIN pegawai pg
ON pb.id_pegawai = pg.id_pegawai;

CREATE VIEW view_stok_bahan AS
SELECT
id_bahan,
nama_bahan,
satuan,
stok
FROM bahan_baku;

CREATE VIEW view_laporan_penjualan AS
SELECT
tanggal_penjualan,
COUNT(id_penjualan) AS jumlah_transaksi,
SUM(total_penjualan) AS total_pendapatan
FROM penjualan
GROUP BY tanggal_penjualan;

DELIMITER $$
CREATE PROCEDURE tambah_menu(
IN p_nama_menu VARCHAR(100),
IN p_harga DECIMAL(10,2)
)
BEGIN
INSERT INTO menu(nama_menu,harga)
VALUES(p_nama_menu,p_harga);
END$$

CREATE PROCEDURE tampil_penjualan()
BEGIN
SELECT
p.id_penjualan,
p.tanggal_penjualan,
pg.nama_pegawai,
p.total_penjualan
FROM penjualan p
JOIN pegawai pg
ON p.id_pegawai=pg.id_pegawai;
END$$

CREATE PROCEDURE tampil_pembelian()
BEGIN
SELECT
pb.id_pembelian,
pb.tanggal_pembelian,
s.nama_supplier,
pg.nama_pegawai,
pb.total_pembelian
FROM pembelian pb
JOIN supplier s
ON pb.id_supplier=s.id_supplier
JOIN pegawai pg
ON pb.id_pegawai=pg.id_pegawai;
END$$

CREATE PROCEDURE cari_penjualan(
IN p_tanggal DATE
)
BEGIN
SELECT *
FROM penjualan
WHERE tanggal_penjualan=p_tanggal;
END$$

CREATE PROCEDURE total_pendapatan()
BEGIN
SELECT
SUM(total_penjualan) AS total_pendapatan
FROM penjualan;
END$$
DELIMITER ;

DELIMITER $$


CREATE TRIGGER trg_tambah_stok
AFTER INSERT ON detail_pembelian
FOR EACH ROW
BEGIN
UPDATE bahan_baku
SET stok = stok + NEW.jumlah
WHERE id_bahan = NEW.id_bahan;
END$$

CREATE TRIGGER trg_kurangi_stok
AFTER INSERT ON detail_penjualan
FOR EACH ROW
BEGIN
UPDATE bahan_baku
SET stok = stok - NEW.jumlah
WHERE id_bahan = 1;
END$$

CREATE TRIGGER trg_total_pembelian
AFTER INSERT ON detail_pembelian
FOR EACH ROW
BEGIN
UPDATE pembelian
SET total_pembelian =
(
SELECT SUM(subtotal)
FROM detail_pembelian
WHERE id_pembelian=NEW.id_pembelian
)
WHERE id_pembelian=NEW.id_pembelian;
END$$

CREATE TRIGGER trg_total_penjualan
AFTER INSERT ON detail_penjualan
FOR EACH ROW
BEGIN
UPDATE penjualan
SET total_penjualan =
(
SELECT SUM(subtotal)
FROM detail_penjualan
WHERE id_penjualan=NEW.id_penjualan
)
WHERE id_penjualan=NEW.id_penjualan;
END$$

DELIMITER ;


DELIMITER $$

DELIMITER $$

DELIMITER $$

DELIMITER $$

DELIMITER $$

