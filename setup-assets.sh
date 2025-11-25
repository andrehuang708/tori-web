#!/bin/bash

# Create necessary directories
echo "Creating asset directories..."
mkdir -p public/assets/{css,js,img,plugins/fontawesome-free/css,plugins/bootstrap/js,plugins/sweetalert2,dist/css,dist/js}

# Download Font Awesome
echo "Downloading Font Awesome..."
wget -q https://use.fontawesome.com/releases/v5.15.4/fontawesome-free-5.15.4-web.zip -O fontawesome.zip
unzip -q fontawesome.zip -d public/assets/plugins/
mv public/assets/plugins/fontawesome-free-5.15.4-web public/assets/plugins/fontawesome-free
rm fontawesome.zip

# Download Bootstrap 4
echo "Downloading Bootstrap 4..."
wget -q https://github.com/twbs/bootstrap/releases/download/v4.6.1/bootstrap-4.6.1-dist.zip -O bootstrap.zip
unzip -q bootstrap.zip -d public/assets/plugins/
mv public/assets/plugins/bootstrap-4.6.1-dist public/assets/plugins/bootstrap
rm bootstrap.zip

# Download jQuery
echo "Downloading jQuery..."
mkdir -p public/assets/plugins/jquery
wget -q https://code.jquery.com/jquery-3.6.0.min.js -O public/assets/plugins/jquery/jquery.min.js
wget -q https://code.jquery.com/jquery-3.6.0.js -O public/assets/plugins/jquery/jquery.js

# Download AdminLTE 3.2.0
echo "Downloading AdminLTE..."
wget -q https://github.com/ColorlibHQ/AdminLTE/releases/download/v3.2.0/AdminLTE-3.2.0.zip -O adminlte.zip
unzip -q adminlte.zip -d public/assets/
mv public/assets/AdminLTE-3.2.0 public/assets/adminlte
cp -r public/assets/adminlte/dist/js/adminlte.min.js public/assets/dist/js/
cp -r public/assets/adminlte/dist/css/adminlte.min.css public/assets/dist/css/
rm -rf public/assets/adminlte adminlte.zip

# Download SweetAlert2
echo "Downloading SweetAlert2..."
wget -q https://cdn.jsdelivr.net/npm/sweetalert2@11.4.4/dist/sweetalert2.all.min.js -O public/assets/plugins/sweetalert2/sweetalert2.all.min.js

# Create a sample hero image
echo "Creating sample hero image..."
wget -q https://via.placeholder.com/1200x400.png/007bff/ffffff?text=Hero+Image -O public/assets/img/hero-image.jpg

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 public/assets/

echo "\nAsset setup complete! The following directories and files have been created:"
echo "- public/assets/plugins/fontawesome-free/"
echo "- public/assets/plugins/bootstrap/"
echo "- public/assets/plugins/jquery/"
echo "- public/assets/plugins/sweetalert2/"
echo "- public/assets/dist/"
echo "- public/assets/img/"

echo "\nYou can now access your assets using base_url('assets/...') in your views."
