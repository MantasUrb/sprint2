<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprint2</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <header>
        <h1>Project</h1>
        <div>
            <a href="?path=projects">Projects</a>
            <a href="?path=employees">Employees</a>
        </div>
    </header>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "mysql";
        $dbname = "company_info";
        $paths = array('projects', 'employees');

        if (isset($_GET['path']) and (in_array($_GET['path'], $paths))) {
            $table = $_GET['path'];
        }

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        if (!isset($_GET['path']) or (isset($_GET['path']) and $_GET['path'] == 'projects')) {
            $sql = "SELECT projects.id as projects_id, projects.project_name as projects_name, GROUP_CONCAT(' ', employees.employee_name) as names 
                        FROM projects
                        LEFT JOIN employees_projects 
                            ON employees_projects.project_id = projects.id
                        LEFT JOIN employees 
                            ON employees.id = employees_projects.employee_id
                        GROUP BY projects.id";
        } elseif (isset($_GET['path']) and $_GET['path'] == 'employees') {
            $sql = "SELECT employees.id as employees_id, employees.employee_name as employees_name, GROUP_CONCAT(' ', projects.project_name) as names
                        FROM employees
                        LEFT JOIN employees_projects 
                            ON employees_projects.employee_id = employees.id
                        LEFT JOIN projects 
                            ON projects.id = employees_projects.project_id
                        GROUP BY employees.id";
        }

        $result = mysqli_query($conn, $sql);
        echo '<table class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        if (!isset($_GET['path']) or $_GET['path'] == 'projects') {
            $title1 = 'Projects';
        } else {
            $title1 = 'Employees';
        }
        echo '<th>' . $title1 . '</th>';
        if (!isset($_GET['path']) or $_GET['path'] == 'projects') {
            $title1 = 'Employees';
        } else {
            $title1 = 'Projects';
        }
        echo '<th>' . $title1 . '</th>';
        echo '</tr>';
        echo '</thead>';

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (!isset($table)) {
                    $table = 'projects';
                }
                echo "<tr>
                        <td>{$row["$table" . "_id"]}</td>
                        <td>{$row["$table" . "_name"]}</td>
                        <td>{$row["names"]}</td>
                      </tr>";
            }
        } else {
            echo "0 results";
        }
        echo ("</table>");
        mysqli_close($conn);
        ?>

</body>

</html>