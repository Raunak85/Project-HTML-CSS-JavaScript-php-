<?php

include 'db_connection.php';

// Fetch job vacancies
$sql = "SELECT job_id, title, company_name, description, location, vacancy_date 
        FROM jobs 
        WHERE vacancy_date >= CURDATE()";
$result = $conn->query($sql);

// Check if there are vacancies
if ($result && $result->num_rows > 0) {
    echo "<table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Vacancy Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['company_name']}</td>
                <td>{$row['description']}</td>
                <td>{$row['location']}</td>
                <td>" . date("F j, Y", strtotime($row['vacancy_date'])) . "</td>
                <td><a href='apply.php?job_id={$row['job_id']}' class='apply-button'>Apply</a></td>
            </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p class='no-data'>No job vacancies available at the moment.</p>";
}

// Close connection
$conn->close();
?>
