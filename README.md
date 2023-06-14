## Judul Project

Smart Aquarium

## Skema Project


## Deskripsi Project

Projek ini dibuat bertujuan untuk mengembangkan alat yang dapat memonitoring akuarium ikan secara otomatis dengan menggunakan IOT (Internet Of Things) untuk mengumpulkan data suhu dan jumlah pakan di akuarium. Sistem ini dapat melakukan monitoring jumlah pakan ikan menggunakan sensor ultrasonik HC-SR04, monitoring suhu akuarium, dan menjalankan pemberian pakan ikan secara otomatis. Diharapkan dengan adanya projek ini dapat memberikan kemudahan dalam monitoring dan pemberian pakan pada akuarium secara otomatis dan dapat dikendalikan dari jarak jauh.

## Fitur Utama

1. **Monitoring Jumlah Pakan:** Sistem ini menggunakan sensor ultrasonik HC-SR04 untuk memantau jumlah pakan yang masih tersedia, dengan menghitung jarak kedalaman dari wadah pakan ikan.
2. **Monitoring Suhu Akuarium:** Sistem ini menggunakan sensor suhu DS18B20 yang dimasukkan kedalam air akuarium untuk memantau suhu air pada akuarium yang dimana jika suhu terlalu dingin maka penghangat akuarium akan dihidupkan.
3. **Pemberian Pakan:** Sistem ini menggunakan servo untuk melakukan pemberian pakan ikan dimana ketika sevo dijalankan pakan akan jatuh ke akuarium. Akan tetapi jika pakan habis pemberian pakan tidak dijalankan.
4. **Penjadwalan Pakan:** Sistem ini dapat melakukan penjadwalan pakan dengan menambahkan jadwal pemberian pakan pada sistem website yang akan berjalan sesuai dengan jam yang sudah ditentukan.
5. **Pengaturan Jenis Ikan Pada Akuarium:** Sistem ini dapat mengatur jenis ikan apa yang ada di akuarium
6. **Mengatur Durasi Pemberian Pakan:** Sistem ini dapat mengatur durasi pemberian pakan dengan mengubah durasi servo yang ada pada pengaturan web. 

## Teknologi Yang Digunakan

- **Bahasa pemrograman:** Php, JavaScript, C++, SQL
- **Mikrokontroller:** Mappi32
- **Protokol IoT:** HTTP
- **Database:** MySQl
- **Komponen:** Sensor Ultrasonik HC-SR04, Sensor Suhu DS18B20, Servo, Buzzer, Relay 5V, LCD 16x2 I2C
