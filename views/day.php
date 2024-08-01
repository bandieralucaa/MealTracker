<?php
include '../php/db.php';
include '../php/auth.php';
check_login();

$date = $_GET['date'];
$user_id = $_SESSION['user_id'];

// Formatta la data in formato italiano
$formatted_date = date('d/m/Y', strtotime($date));

$sql = "SELECT * FROM meal WHERE user_id='$user_id' AND date='$date'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MealTracker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Pasti del <?php echo $formatted_date; ?></h2>
        <a href="calendar.php" class="btn btn-secondary mb-3">Torna al Calendario</a>
        <a href="add_meal_form.php?date=<?php echo $date; ?>" class="btn btn-primary mb-3">Aggiungi Pasto</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Tipo di Pasto</th>
                    <th>Cibo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr id="meal-<?php echo $row['id']; ?>">
                    <td><?php echo $row['meal_type']; ?></td>
                    <td><?php echo $row['food']; ?></td>
                    <td>
                        <button class="btn btn-warning edit-meal" data-id="<?php echo $row['id']; ?>" data-meal-type="<?php echo $row['meal_type']; ?>" data-food="<?php echo $row['food']; ?>">Modifica</button>
                        <button class="btn btn-danger delete-meal" data-id="<?php echo $row['id']; ?>">Elimina</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal per la modifica del pasto -->
    <div class="modal fade" id="editMealModal" tabindex="-1" role="dialog" aria-labelledby="editMealModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMealModalLabel">Modifica Pasto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editMealForm">
                        <input type="hidden" id="editMealId" name="meal_id">
                        <div class="form-group">
                            <label for="editMealType">Tipo di Pasto</label>
                            <select class="form-control" id="editMealType" name="meal_type" required>
                                <option value="colazione">Colazione</option>
                                <option value="spuntino">Spuntino</option>
                                <option value="pranzo">Pranzo</option>
                                <option value="merenda">Merenda</option>
                                <option value="cena">Cena</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editFood">Cibo</label>
                            <input type="text" class="form-control" id="editFood" name="food" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Modifica</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Gestione del click sul bottone "Modifica"
            $('.edit-meal').click(function() {
                var mealId = $(this).data('id');
                var mealType = $(this).data('meal-type');
                var food = $(this).data('food');

                $('#editMealId').val(mealId);
                $('#editMealType').val(mealType);
                $('#editFood').val(food);

                $('#editMealModal').modal('show');
            });

            // Gestione del submit del form di modifica
            $('#editMealForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    url: '../php/edit_meal.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === 'success') {
                            location.reload();
                        } else {
                            alert('Errore durante la modifica del pasto.');
                        }
                    }
                });
            });

            // Gestione del click sul bottone "Elimina"
            $('.delete-meal').click(function() {
                var mealId = $(this).data('id');
                var row = $('#meal-' + mealId);

                if (confirm('Sei sicuro di voler eliminare questo pasto?')) {
                    $.ajax({
                        url: '../php/delete_meal.php',
                        type: 'POST',
                        data: { meal_id: mealId },
                        success: function(response) {
                            if (response === 'success') {
                                row.remove();
                            } else {
                                alert('Errore durante l\'eliminazione del pasto.');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
