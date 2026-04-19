<?php
class BackupController extends Controller {
    
    public function index() {
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
        }
        
        $backupDir = '/var/backups/bibliogest';
        $backups = [];
        $totalSize = 0;
        
        if (is_dir($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if (preg_match('/bibliogest_.*\.sql\.gz/', $file)) {
                    $path = $backupDir . '/' . $file;
                    $size = filesize($path);
                    $totalSize += $size;
                    $backups[] = [
                        'name' => $file,
                        'size' => $this->formatSize($size),
                        'date' => date('d/m/Y H:i:s', filemtime($path)),
                        'path' => $path
                    ];
                }
            }
            rsort($backups);
        }
        
      //  $this->view('admin/backups', ['backups' => $backups]);
      // Récupérer les compteurs pour la sidebar
    $book = new Book();
    $allBooks = $book->findAll();
    $totalBooks = count($allBooks);
    
    $loan = new Loan();
    $userLoans = $loan->getUserLoans($_SESSION['user_id']);
    $activeLoans = 0;
    foreach ($userLoans as $l) {
        if ($l['status'] == 'en_cours') $activeLoans++;
    }
    
    $user = new User();
    $allUsers = $user->findAll();
    $totalUsers = count($allUsers);
    
    $reservation = new Reservation();
    $userReservations = $reservation->getUserReservations($_SESSION['user_id']);
    $totalReservations = 0;
    foreach ($userReservations as $r) {
        if ($r['status'] == 'active') $totalReservations++;
    }
    
    $lastBackup = !empty($backups) ? $backups[0]['date'] : 'Jamais';
    $totalSizeFormatted = $this->formatSize($totalSize);
    
    $this->view('admin/backups', [
        'backups' => $backups,
        'totalSize' => $totalSizeFormatted,
        'lastBackup' => $lastBackup,
        'totalBooks' => $totalBooks,
        'activeLoans' => $activeLoans,
        'totalUsers' => $totalUsers,
        'totalReservations' => $totalReservations
    ]);
    }
    
    public function create() {
        if (!$this->isAdmin()) {
            $this->json(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        $script = '/var/www/html/bibliogest/scripts/backup.sh';
        exec($script . ' 2>&1', $output, $returnCode);
        
        if ($returnCode === 0) {
            $this->json(['success' => true, 'message' => 'Backup créé avec succès']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors du backup']);
        }
    }
    
    public function download() {
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
        }
        
        $file = $_GET['file'] ?? '';
        $backupDir = '/var/backups/bibliogest';
        $filePath = $backupDir . '/' . $file;
        
        if (file_exists($filePath) && preg_match('/bibliogest_.*\.sql\.gz/', $file)) {
            header('Content-Type: application/gzip');
            header('Content-Disposition: attachment; filename="' . $file . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            $this->redirect('/admin/backups');
        }
    }
    
    public function delete() {
        if (!$this->isAdmin()) {
            $this->json(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $file = $data['file'] ?? '';
        $backupDir = '/var/backups/bibliogest';
        $filePath = $backupDir . '/' . $file;
        
        if (file_exists($filePath) && preg_match('/bibliogest_.*\.sql\.gz/', $file)) {
            unlink($filePath);
            $this->json(['success' => true, 'message' => 'Backup supprimé']);
        } else {
            $this->json(['success' => false, 'message' => 'Fichier non trouvé']);
        }
    }
    
    private function formatSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
?>
