CREATE DATABASE IF NOT EXISTS pkkmb_mahasiswa;

USE pkkmb_mahasiswa;

-- Create the mahasiswa table
CREATE TABLE IF NOT EXISTS mahasiswa (
    NIM VARCHAR(15) PRIMARY KEY,
    Admin_NIM VARCHAR(15),
    Cluster_ID INT,
    Nama VARCHAR(100),
    Prodi VARCHAR(100),
    Email VARCHAR(100),
    Password VARCHAR(255) -- Store hashed passwords
);

-- Insert example data into mahasiswa table
INSERT INTO mahasiswa (NIM, Admin_NIM, Cluster_ID, Nama, Prodi, Email, Password) VALUES
('22515010111015', '22515010111005', 5, 'Rizky Hidayat', 'Pendidikan Teknologi Informasi', 'rizky.hidayat@student.ub.ac.id', '2345'), 
('22515010111029', '22515010111005', 5, 'Afiif Hauzan', 'Pendidikan Teknologi Informasi', 'afiif@student.ub.ac.id', '1245');

select * from mahasiswa;

