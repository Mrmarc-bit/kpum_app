#!/bin/bash
echo "Starting 3 Queue Workers..."

# Start 3 workers in background
for i in {1..3}
do
    echo "Starting worker #$i..."
    php artisan queue:work --timeout=1800 --tries=3 &
done

echo "All workers started!"
wait
