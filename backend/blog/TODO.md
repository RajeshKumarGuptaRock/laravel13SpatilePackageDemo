# TODO - Sales module with invoice + stock decrement

## Steps
- [ ] Add `products.quantity` column migration (default 0)
- [ ] Create migrations for `sales` and `sale_items`
- [ ] Create models: `Sale`, `SaleItem` and relationships
- [ ] Add SalesController with index/create/store/show/print
- [ ] Build Blade views for:
  - [ ] sales/create: customer form + multiple product selection with qty + total
  - [ ] sales/show: invoice
  - [ ] sales/print: printable invoice
- [ ] Update Product model to include `quantity` fillable (if needed)
- [ ] Implement store() business logic:
  - [ ] validate items + compute totals
  - [ ] decrement `products.quantity` by qty per item
  - [ ] create `stock_movements` out records with reference to sale
- [ ] Add permissions for sales into `RolePermissionSeeder.php`
- [ ] Add Sales sidebar entries into `resources/views/partials/sidebar.blade.php`
- [ ] Run migrations + clear caches
- [ ] Verify UI end-to-end: create sale -> save -> invoice -> print

