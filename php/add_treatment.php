<?php
// add_treatment.php
function insertTreatment($conn, $treatment_id, $nama_treatment, $deskripsi, $harga, $estimasi) {
    try {
        $query = "INSERT INTO treatmen (Treatment_ID, Nama_Treatment, Deskripsi, Harga, Estimasi) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssis", $treatment_id, $nama_treatment, $deskripsi, $harga, $estimasi);
        return $stmt->execute(); // Return true if insert is successful, false otherwise
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        return false; // Return false on error
    }
}
?>
