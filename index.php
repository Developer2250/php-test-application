<?php
//include database file
require 'db.php';

function buildTree($parentId, $conn)
{
    $sql = "SELECT * FROM members WHERE ParentId " . ($parentId ? "= $parentId" : "IS NULL");
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['Name']);
            buildTree($row['Id'], $conn);
            echo "</li>";
        }
        echo "</ul>";
    }
}

//get all members in dropdown 
function getAllMembers($conn)
{
    $sql = "SELECT Id, Name FROM members";
    $result = $conn->query($sql);
    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
    return $members;
}

$members = getAllMembers($conn);

?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="my-4">Member List</h1>

        <?php buildTree(null, $conn); ?>

        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addMemberModal">
            Add Member
        </button>

        <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMemberModalLabel">Add Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addMemberForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="Name">
                                <div id="nameError" class="text-danger mt-2" style="display:none;">Name must contain only letters and cannot be empty.</div>
                            </div>
                            <div class="mb-3">
                                <label for="parent" class="form-label">Parent</label>
                                <select class="form-select" id="parent" name="parent">
                                    <option value="">None</option>
                                    <?php foreach ($members as $member): ?>
                                        <option value="<?= $member['Id'] ?>"><?= htmlspecialchars($member['Name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $("#addMemberForm").on("submit", function(e) {
                e.preventDefault();

                const name = $("#name").val();
                const parent = $("#parent").val();

                //name field validation
                if (!name || !/^[a-zA-Z\s]+$/.test(name)) {
                    $("#nameError").show();
                    return;
                } else {
                    $("#nameError").hide();
                }

                $.ajax({
                    url: "insert.php",
                    type: "POST",
                    data: {
                        name: name,
                        parentId: parent
                    },
                    success: function(response) {
                        $("#addMemberModal").modal("hide");
                        location.reload();
                    },
                    error: function() {
                        alert("An error occurred while adding the member.");
                    }
                });
            });
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>