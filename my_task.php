<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

    $tasks = get_all_tasks_by_id($conn, $_SESSION['id']);

    $unique_statuses = [];
    if ($tasks != 0) {
        foreach ($tasks as $task) {
            $s = trim($task['status']);
            if ($s !== '' && !in_array($s, $unique_statuses)) {
                $unique_statuses[] = $s;
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Tasks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .main-table {
            font-size: 1rem;
            width: 100%;
            table-layout: fixed;
        }

        .table-wrapper {
            display: inline-block;
            width: 100%;
        }

        .table-toolbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            width: 100%;
        }

        .table-toolbar label {
            font-size: 0.95rem;
            font-weight: 600;
        }

        #statusFilter {
            font-size: 0.9rem;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        #noResults {
            display: none;
            font-size: 1rem;
            color: #888;
            padding: 12px;
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class="title">My Tasks</h4>

            <?php if (isset($_GET['success'])) { ?>
                <div class="success" role="alert">
                    <?php echo stripcslashes($_GET['success']); ?>
                </div>
            <?php } ?>

            <?php if ($tasks != 0) { ?>

                <div class="table-wrapper">
                    <div class="table-toolbar">
                        <label for="statusFilter">Filter by Status:</label>
                        <select id="statusFilter">
                            <option value="All">All</option>
                            <?php foreach ($unique_statuses as $status) { ?>
                                <option value="<?= htmlspecialchars($status) ?>">
                                    <?= htmlspecialchars($status) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <table class="main-table" id="taskTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="taskBody">
                            <?php $i = 0;
                            foreach ($tasks as $task) { ?>
                                <tr data-status="<?= htmlspecialchars(trim($task['status'])) ?>">
                                    <td class="row-num"><?= ++$i ?></td>
                                    <td><?= htmlspecialchars($task['title']) ?></td>
                                    <td><?= htmlspecialchars($task['description']) ?></td>
                                    <td><?= htmlspecialchars(trim($task['status'])) ?></td>
                                    <td><?= ($task['due_date'] == "") ? "No Deadline" : htmlspecialchars($task['due_date']) ?></td>
                                    <td>
                                        <a href="edit-task-employee.php?id=<?= $task['id'] ?>" class="edit-btn">Edit</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr id="noResults">
                                <td colspan="6">No tasks match the selected status.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            <?php } else { ?>
                <h3>Empty</h3>
            <?php } ?>

        </section>
    </div>

<script type="text/javascript">
    var active = document.querySelector("#navList li:nth-child(2)");
    active.classList.add("active");

    const statusFilter = document.getElementById('statusFilter');
    const noResults    = document.getElementById('noResults');

    function applyStatusFilter() {
        const selected = statusFilter.value.trim().toLowerCase();
        const rows     = document.querySelectorAll('#taskBody tr[data-status]');
        let   visible  = 0;

        rows.forEach(function(row) {
            const rowStatus = row.getAttribute('data-status').trim().toLowerCase();
            const show      = (selected === 'all' || rowStatus === selected);

            row.style.display = show ? '' : 'none';
            if (show) row.querySelector('.row-num').textContent = ++visible;
        });

        noResults.style.display = (visible === 0) ? '' : 'none';
    }

    statusFilter.addEventListener('change', applyStatusFilter);
</script>
</body>
</html>
<?php } else {
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
} ?>