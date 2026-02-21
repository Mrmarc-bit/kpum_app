#!/bin/bash

echo "================================================"
echo "  KPUM Queue Worker - Auto-Start Installer"
echo "================================================"
echo ""

SERVICE_FILE="kpum-queue-worker.service"
SYSTEMD_PATH="/etc/systemd/system/$SERVICE_FILE"

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo "⚠️  This script needs sudo access to install systemd service."
    echo ""
    echo "Please run with sudo:"
    echo "  sudo bash install_worker_service.sh"
    echo ""
    exit 1
fi

echo "📋 Installing systemd service..."
cp "$SERVICE_FILE" "$SYSTEMD_PATH"

echo "🔄 Reloading systemd daemon..."
systemctl daemon-reload

echo "✅ Enabling service (auto-start on boot)..."
systemctl enable kpum-queue-worker

echo "🚀 Starting service now..."
systemctl start kpum-queue-worker

echo ""
echo "================================================"
echo "  ✅ Installation Complete!"
echo "================================================"
echo ""
echo "Worker is now running and will:"
echo "  • Start automatically on boot"
echo "  • Restart automatically if it crashes"
echo "  • Run in background (no terminal needed)"
echo ""
echo "Useful commands:"
echo "  • Check status:  sudo systemctl status kpum-queue-worker"
echo "  • View logs:     tail -f storage/logs/queue-worker.log"
echo "  • Restart:       sudo systemctl restart kpum-queue-worker"
echo "  • Stop:          sudo systemctl stop kpum-queue-worker"
echo ""
