💎 Hayati Diamond - Business Management System
A dedicated management platform for mid-sized jewelry businesses, specifically designed to handle gemstone inventory and a structured "Installment-to-Own" (ออมทอง/ออมเพชร) business model.

✨ Key Features
🛠 Practical Inventory Management
Product Categorization: Manage various jewelry items, from affordable pieces (10k+) to premium collections (up to 200k).

Gold Price Synchronization: Centralized gold price setting to ensure product valuations reflect current market rates.

Stock Status Control: Professional tracking of item availability, specifically managing reserved vs. available stock.

💳 Savings & Installment Tracking (ระบบออมสินค้า)
Interest-Free Installment Logic: Automated calculation of equal monthly payments based on the product’s total value and the chosen duration.

Progressive Payment Monitoring: A rigorous tracking system to monitor every payment made towards a specific item.

Full-Payment Delivery Lock: Business logic that ensures item delivery status is only cleared once the balance reaches zero.

📄 Financial Documentation
Automated Transaction Receipts: Professional PDF generation for every installment payment using mPDF.

Internal Asset Integrity: All document assets (logos/images) are sourced locally to guarantee that historical receipts remain accurate and visually complete, regardless of external network status.

🛠 Tech Stack
Framework: Laravel 12 (PHP 8.2+)

Database: PostgreSQL (Cloud Managed via Supabase)

Frontend: Blade Templates & Tailwind CSS

Deployment: Containerized with Docker on Render

💡 Engineering Highlights
Reliable Document Generation: By moving from external cloud URLs to local file sourcing for PDFs, the system ensures that transactional records are permanent and immune to external link expirations.

Precise Financial Tracking: Implemented custom logic to handle installment schedules, focusing on tracking accumulated payments until they match the fixed product price—critical for the "delivery upon full payment" model.

📝 Project Context
This system is tailored for mid-range jewelry operations, focusing on providing a secure and organized way for customers to save up for high-value items while allowing business owners to manage inventory with high precision.
