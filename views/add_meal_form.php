<?php
include '../php/db.php';
include '../php/auth.php';
check_login();

$date = $_GET['date'];
$user_id = $_SESSION['user_id'];

// Calcola la data di 48 ore fa
$two_days_ago = date('Y-m-d', strtotime('-2 days', strtotime($date)));

// Estrai i cibi consumati nelle ultime 48 ore
$recent_meals_sql = "SELECT DISTINCT food FROM meal WHERE user_id='$user_id' AND date BETWEEN '$two_days_ago' AND '$date'";
$recent_meals_result = $conn->query($recent_meals_sql);

$recent_meals = [];
while ($row = $recent_meals_result->fetch_assoc()) {
    $recent_meals[] = $row['food'];
}

// Estrai i cibi disponibili per ogni tipo di pasto, escludendo quelli consumati nelle ultime 48 ore
$breakfast_sql = "SELECT * FROM breakfast WHERE food NOT IN ('" . implode("','", $recent_meals) . "')";
$snack_sql = "SELECT * FROM snack WHERE food NOT IN ('" . implode("','", $recent_meals) . "')";
$launch_dinner_sql = "SELECT * FROM launch_dinner WHERE food NOT IN ('" . implode("','", $recent_meals) . "')";

$breakfast_result = $conn->query($breakfast_sql);
$snack_result = $conn->query($snack_sql);
$launch_dinner_result = $conn->query($launch_dinner_sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MealTracker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../src/logo.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Aggiungi Pasto per il <?php echo $date; ?></h2>
        <form action="../php/add_meal.php" method="post">
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <div class="form-group">
                <label for="meal_type">Tipo di Pasto</label>
                <select class="form-control" id="meal_type" name="meal_type" required>
                    <option value="colazione">Colazione</option>
                    <option value="spuntino">Spuntino</option>
                    <option value="pranzo">Pranzo</option>
                    <option value="merenda">Merenda</option>
                    <option value="cena">Cena</option>
                </select>
            </div>
            <div class="form-group">
                <label for="food">Cibo</label>
                <select class="form-control" id="food" name="food" required>
                    <!-- Popola questa select con i cibi disponibili dal database -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi</button>
        </form>
    </div>

    <script>
        document.getElementById('meal_type').addEventListener('change', function() {
            var mealType = this.value;
            var foodSelect = document.getElementById('food');
            foodSelect.innerHTML = ''; // Svuota la select

            var foods = [];

            if (mealType === 'colazione') {
                foods = <?php echo json_encode($breakfast_result->fetch_all(MYSQLI_ASSOC)); ?>;
            } else if (mealType === 'spuntino' || mealType === 'merenda') {
                foods = <?php echo json_encode($snack_result->fetch_all(MYSQLI_ASSOC)); ?>;
            } else if (mealType === 'pranzo' || mealType === 'cena') {
                foods = <?php echo json_encode($launch_dinner_result->fetch_all(MYSQLI_ASSOC)); ?>;
            }

            foods.forEach(function(food) {
                var option = document.createElement('option');
                option.value = food.food;
                option.text = food.food;
                foodSelect.appendChild(option);
            });
        });

        // Trigger change event to populate the food select on page load
        document.getElementById('meal_type').dispatchEvent(new Event('change'));
    </script>
</body>
</html>
