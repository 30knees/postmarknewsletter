#!/bin/bash
# PrestaShop Module Build Script
# This script packages the module for distribution

MODULE_NAME="postmarknewsletter"
BUILD_DIR="build"
DIST_DIR="dist"

echo "========================================="
echo "Building PrestaShop Module: $MODULE_NAME"
echo "========================================="

# Clean up previous builds
echo "Cleaning up previous builds..."
rm -rf "$BUILD_DIR" "$DIST_DIR"
mkdir -p "$BUILD_DIR/$MODULE_NAME"
mkdir -p "$DIST_DIR"

# Copy module files
echo "Copying module files..."
# Create a list of files to copy
find . -type f \
  ! -path "./.git/*" \
  ! -path "./build/*" \
  ! -path "./dist/*" \
  ! -path "./examples/*" \
  ! -path "./vendor/*" \
  ! -name "build.sh" \
  ! -name ".gitignore" \
  ! -name "composer.lock" \
  ! -name "CONTRIBUTING.md" \
  ! -name "INSTALL.md" \
  | while read file; do
    # Create directory structure
    dir=$(dirname "$file")
    mkdir -p "$BUILD_DIR/$MODULE_NAME/$dir"
    # Copy file
    cp "$file" "$BUILD_DIR/$MODULE_NAME/$file"
  done

# Install Composer dependencies (optimized for production)
echo "Installing Composer dependencies..."
cd "$BUILD_DIR/$MODULE_NAME"
if [ -f "composer.json" ]; then
  composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction 2>/dev/null || {
    echo "Note: Composer not available or no dependencies to install"
  }
fi
cd - > /dev/null

# Create zip file
echo "Creating zip archive..."
cd "$BUILD_DIR"
zip -r "../$DIST_DIR/$MODULE_NAME.zip" "$MODULE_NAME" -q
cd - > /dev/null

# Calculate file size
FILE_SIZE=$(du -h "$DIST_DIR/$MODULE_NAME.zip" | cut -f1)

echo ""
echo "========================================="
echo "Build completed successfully!"
echo "========================================="
echo "Output: $DIST_DIR/$MODULE_NAME.zip"
echo "Size: $FILE_SIZE"
echo ""
echo "To install:"
echo "1. Upload $MODULE_NAME.zip to your PrestaShop admin"
echo "2. Go to Modules > Module Manager"
echo "3. Click 'Upload a module' and select the zip file"
echo "========================================="
