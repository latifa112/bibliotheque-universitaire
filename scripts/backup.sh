#!/bin/bash
# Script de sauvegarde automatique de la base de données

# Configuration
DB_USER="bibliogest_user"
DB_PASS="bibliogest123"
DB_NAME="bibliogest"
BACKUP_DIR="/var/backups/bibliogest"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30

# Créer le dossier de backup s'il n'existe pas
mkdir -p $BACKUP_DIR

# Nom du fichier de backup
BACKUP_FILE="$BACKUP_DIR/bibliogest_$DATE.sql"

# Effectuer le backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_FILE 2>/dev/null

# Vérifier si le backup a réussi
if [ $? -eq 0 ]; then
    echo "$DATE - Backup réussi : $BACKUP_FILE" >> $BACKUP_DIR/backup.log
    # Compresser le fichier
    gzip $BACKUP_FILE
    echo "$DATE - Compression terminée" >> $BACKUP_DIR/backup.log
else
    echo "$DATE - ERREUR : Échec du backup" >> $BACKUP_DIR/backup.log
fi

# Supprimer les backups de plus de 30 jours
find $BACKUP_DIR -name "bibliogest_*.sql.gz" -mtime +$RETENTION_DAYS -delete
echo "$DATE - Nettoyage des anciens backups" >> $BACKUP_DIR/backup.log
