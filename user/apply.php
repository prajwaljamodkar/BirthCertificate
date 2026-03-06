<?php
/**
 * user/apply.php — Application form for birth certificate.
 * On submission, saves data to the database with status 'pending'.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/db.php';

requireRole('user');

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $fname       = trim($_POST['fname']       ?? '');
    $mname       = trim($_POST['mname']       ?? '');
    $lname       = trim($_POST['lname']       ?? '');
    $birthdate   = trim($_POST['birthdate']   ?? '');
    $bplace      = trim($_POST['bplace']      ?? '');
    $gender      = trim($_POST['gender']      ?? '');
    $father_name = trim($_POST['father_name'] ?? '');
    $mother_name = trim($_POST['mother_name'] ?? '');
    $religion    = trim($_POST['religion']    ?? '');
    $category    = trim($_POST['category']    ?? '');

    // Basic validation
    $allowed_genders   = ['Male', 'Female', 'Other'];
    $allowed_religions = ['Hinduism', 'Buddhism', 'Islam', 'Christianity', 'Other'];
    $allowed_categories = ['Open', 'OBC', 'SC', 'ST', 'Other'];

    if ($fname === '' || $lname === '' || $birthdate === '' || $bplace === ''
        || $father_name === '' || $mother_name === '') {
        $error = 'Please fill in all required fields.';
    } elseif (!in_array($gender, $allowed_genders, true)) {
        $error = 'Invalid gender selection.';
    } elseif (!in_array($religion, $allowed_religions, true)) {
        $error = 'Invalid religion selection.';
    } elseif (!in_array($category, $allowed_categories, true)) {
        $error = 'Invalid category selection.';
    } else {
        $pdo  = getDB();
        $stmt = $pdo->prepare(
            'INSERT INTO applications
                (user_id, fname, mname, lname, birthdate, bplace, gender,
                 father_name, mother_name, religion, category, status)
             VALUES
                (:uid, :fn, :mn, :ln, :bd, :bp, :gn, :fat, :mot, :rel, :cat, :st)'
        );
        $stmt->execute([
            ':uid' => $_SESSION['user_id'],
            ':fn'  => $fname,
            ':mn'  => $mname,
            ':ln'  => $lname,
            ':bd'  => $birthdate,
            ':bp'  => $bplace,
            ':gn'  => $gender,
            ':fat' => $father_name,
            ':mot' => $mother_name,
            ':rel' => $religion,
            ':cat' => $category,
            ':st'  => 'pending',
        ]);
        $success = 'Application submitted successfully! You can track the status on your dashboard.';
    }
}

$pageTitle = 'Apply for Birth Certificate';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Apply for Birth Certificate</h2>
    <p style="margin-top:6px;color:#555;font-size:0.9rem;">
        Fill in all details accurately. Your application will be reviewed by the authorities.
    </p>
</div>

<div class="card">
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success); ?>
            <a href="<?php echo BASE_URL; ?>user/dashboard.php">Go to Dashboard</a>
        </div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="post" action="">
        <label for="fname">First Name <span style="color:red">*</span></label>
        <input type="text" id="fname" name="fname" required
               value="<?php echo htmlspecialchars($_POST['fname'] ?? ''); ?>" />

        <label for="mname">Middle Name</label>
        <input type="text" id="mname" name="mname"
               value="<?php echo htmlspecialchars($_POST['mname'] ?? ''); ?>" />

        <label for="lname">Last Name <span style="color:red">*</span></label>
        <input type="text" id="lname" name="lname" required
               value="<?php echo htmlspecialchars($_POST['lname'] ?? ''); ?>" />

        <label for="birthdate">Birth Date <span style="color:red">*</span></label>
        <input type="date" id="birthdate" name="birthdate" required
               value="<?php echo htmlspecialchars($_POST['birthdate'] ?? ''); ?>" />

        <label for="bplace">Birth Place <span style="color:red">*</span></label>
        <input type="text" id="bplace" name="bplace" required
               value="<?php echo htmlspecialchars($_POST['bplace'] ?? ''); ?>" />

        <label>Gender <span style="color:red">*</span></label>
        <div class="radio-group">
            <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                <label>
                    <input type="radio" name="gender" value="<?php echo $g; ?>"
                        <?php echo (($_POST['gender'] ?? '') === $g) ? 'checked' : ''; ?> required />
                    <?php echo $g; ?>
                </label>
            <?php endforeach; ?>
        </div>

        <label for="father_name">Father's Name <span style="color:red">*</span></label>
        <input type="text" id="father_name" name="father_name" required
               value="<?php echo htmlspecialchars($_POST['father_name'] ?? ''); ?>" />

        <label for="mother_name">Mother's Name <span style="color:red">*</span></label>
        <input type="text" id="mother_name" name="mother_name" required
               value="<?php echo htmlspecialchars($_POST['mother_name'] ?? ''); ?>" />

        <label>Religion <span style="color:red">*</span></label>
        <div class="radio-group">
            <?php foreach (['Hinduism' => 'Hindu', 'Buddhism' => 'Buddhist', 'Islam' => 'Muslim',
                             'Christianity' => 'Christian', 'Other' => 'Other'] as $val => $label): ?>
                <label>
                    <input type="radio" name="religion" value="<?php echo $val; ?>"
                        <?php echo (($_POST['religion'] ?? '') === $val) ? 'checked' : ''; ?> required />
                    <?php echo $label; ?>
                </label>
            <?php endforeach; ?>
        </div>

        <label>Category <span style="color:red">*</span></label>
        <div class="radio-group">
            <?php foreach (['Open', 'OBC', 'SC', 'ST', 'Other'] as $cat): ?>
                <label>
                    <input type="radio" name="category" value="<?php echo $cat; ?>"
                        <?php echo (($_POST['category'] ?? '') === $cat) ? 'checked' : ''; ?> required />
                    <?php echo $cat; ?>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit Application</button>
            <a href="<?php echo BASE_URL; ?>user/dashboard.php" class="btn btn-secondary"
               style="margin-left:10px;">Cancel</a>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
