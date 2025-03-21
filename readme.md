# CHECK24 Code Challenge

A Symfony-based application that imports credit card data from an external API, displays it in a sortable list, and allows non-technical users to edit card details while ensuring changes persist across API updates.

## 🚀 Features

### 🔄 Data Import
- Fetches credit card data from an external **web service API**.
- Updates or inserts records into the **local MySQL database**.

### 📄 Results Page
- Displays a **sortable list** of credit cards.
- Users can sort by:
    - **Annual Fee (`annualFee`)**
    - **Card Name (`name`)**
- Sorting works in **ascending** and **descending** order.

### 🖊️ Editing
- Users can **edit credit card details**:
    - **Name**
    - **Annual Fee**
    - **Remarks**
- Changes are **stored separately** to persist across API updates.

### ✅ Feature Highlights
- Key card features (e.g., `"No annual fee!"`, `"High TAE!"`) are:
    - **Automatically identified**
    - **Displayed with checkmarks** (✔) or **warnings** (!)
- This filtering is **handled in the frontend** but can also be managed in backend services.

---

## 🔍 Additional Notes
- 🛠 **Filtering Logic**: Implemented in the backend but **not mandatory**.
- 🎨 **Feature Differences**: Currently handled in the frontend but can be moved to services.
- 🧪 **Testing**: Some unit and functional tests are included.

---

## 🏗️ Design Principles

### 📌 SOLID Principles
- **Single Responsibility Principle (SRP)** → **Separation of concerns** (Repositories, Services, Controllers).
- **Dependency Inversion Principle (DIP)** → Uses interfaces (`RepositoryInterface`, `CardServiceInterface`).

### 📦 Clean Code & Partial DDD
- Uses **Entities, Repositories, and Services** for separation of concerns.
- Business logic is **encapsulated within services and repositories**, keeping controllers lightweight.

