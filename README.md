# Bookland - Educational Management System

A comprehensive Laravel-based educational management system built with Blade templating, Tailwind CSS, and Alpine.js. The application manages school resources, book adoptions, training, events, and quality compliance.

## 🎯 Project Overview

**Bookland** is a finalized project (PFE - Projet de Fin d'Études) designed to streamline educational institution management with role-based access control and comprehensive resource management capabilities.

### Tech Stack
- **Backend**: Laravel Framework
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: PostgreSQL/MySQL
- **Language Composition**: Blade (82.2%) + PHP (17.8%)

---

## 📋 Core Features & Modules

### 1. **User & Role Management**
- **Role-Based Access Control**: Three main roles
  - **Admin**: Full system access
  - **RBO** (Regional Business Officer): Regional oversight
  - **Délégué** (Delegate): Account-specific operations
- User CRUD operations with role assignment
- Zone and Ville (City) assignments to users
- User authentication via Laravel Breeze

### 2. **Geographic Hierarchy Management**
- **Villes** (Cities) - Admin only
- **Zones** (Zones) - Admin only  
- **Quartiers** (Districts) - Admin only
- Dynamic assignment of Zones to Villes

### 3. **Account & Contact Management**
- **Comptes** (Accounts) - CRUD operations (Admin, RBO, Délégué)
- **Contacts** - Complete contact management
- Contact-to-Account linking
- Filtered account access based on user role and assignments

### 4. **Product Management**
- **Products** (Books/Educational Materials) - Admin only CRUD
- Bulk product import functionality
- Product search by title, ISBN-13, ISBN-10, or author
- Product categorization and subcategories

### 5. **Educational Management**

#### **Adoptions** (Book Adoptions)
- Create manual adoption records (Delegates & Admin)
- Convert BSS records to adoptions
- Print adoption lists and individual adoption sheets
- Adoption tracking and validation
- View all adoption history (Admin, RBO, Délégué)

#### **Effectifs** (Student Numbers/Class Sizes)
- Track student counts per level/class
- Row-level validation (Admin, RBO)
- Devalidation capabilities
- Grouped by educational level

#### **Examens** (Examinations)**
- Create and manage examinations
- Status change functionality
- Delegate-specific exam creation (Admin, RBO can create for Délégués)
- Print examination records

#### **Formations** (Training)**
- Training session management
- Status change and tracking
- Delegate-specific training creation
- Comprehensive training history

#### **Consignations** (Book Shipments)**
- Create and manage book consignments
- Link to BSS (Book Specimen Sheets)
- Admin-only creation and editing
- Consignment tracking and validation

### 6. **Book Specimen System (BSS)**
- Create Book Specimen Sheets
- Edit and update specimen records
- Conversion to adoptions
- Print BSS lists and individual sheets
- Status tracking: valide, livre, retour

### 7. **Return Management**
- Track book returns
- Associate returns with BSS records
- Admin and Délégué return creation
- Return history and reporting

### 8. **Request Management**
- **Demandes Specimens** (Specimen Requests)
  - Request specimen copies
  - Validation workflow
  - Request approval/rejection
  - Print request lists

### 9. **Event Management**
- Create and manage events
- Invite contacts to events
- Event statistics and attendance tracking
- Update contact attendance status
- Event rescheduling

### 10. **Quality & Compliance Management**

#### **Actions** (Action Items)**
- Create action items with categories
- Status tracking: réalisée, validée, annulée, reportée
- Associate with moyens (resources)
- Admin/RBO can create actions for Délégués
- Action validation workflow

#### **Actions d'Amélioration** (Improvement Actions)**
- Track improvement initiatives
- PDF export functionality
- Follow-up tracking with custom suivi updates
- Efficacy assessment and updates

#### **Réclamations** (Complaints/Claims)**
- File and manage claims
- Track complaint status
- PDF export reports

#### **Non-Conformités** (Non-Conformities)**
- Record quality non-conformities
- Efficacy assessment workflow
- PDF export capabilities
- Remediation tracking

### 11. **Task Management**
- **Tâches** (Tasks)
  - Create, edit, delete tasks
  - Task validation workflow
  - Recurring task support with cancellation
  - Admin/RBO can create tasks for Délégués
  - Task status tracking

### 12. **Maintenance Planning**
- **Vacations** (Scheduled Maintenance)
  - Define maintenance schedules
  - Admin only access
  - Integration with calendar/agenda

### 13. **Calendar & Agenda**
- **Agenda** - Unified calendar view
- Event rescheduling interface
- View all scheduled items (adoptions, exams, formations, events, actions, tasks)
- Real-time event updates

### 14. **Dashboard**
- Overview statistics
- Real-time dashboard stats API
- Quick access to key metrics

### 15. **Notification System**
- Notification center
- Mark notifications as read
- Unread notification count
- Notification API endpoints

### 16. **Material/Product Delivery**
- **MP Deliveries** - Track material/product delivery shipments
- Create, view, and delete delivery records
- Integration with product management

### 17. **Logging & Audit Trail**
- System-wide logging
- Admin access to logs
- Track user actions and system events

### 18. **Academic Years Management**
- **Années Scolaires** (Academic Years)
- Set active academic year
- Close academic years
- Track multiple concurrent years

---

## 🔐 Role-Based Access Matrix

| Feature | Admin | RBO | Délégué |
|---------|:-----:|:---:|:-------:|
| Geographic Management | ✓ | ✗ | ✗ |
| User CRUD | ✓ | ✗ | ✗ |
| Users › Roles Page | ✓* | ✓* | ✓* |
| Comptes CRUD | ✓* | ✓* | ✓* |
| Products Management | ✓ | ✗ | ✗ |
| Adoptions Create | ✓ | ✗ | ✓ |
| BSS Management | ✓ | Limited | ✓ |
| Actions Create | ✓ | Limited | ✓ |
| Quality Management | ✓ | ✓ | Limited |
| Logs View | ✓ | ✗ | ✗ |
| Academic Years | ✓ | ✗ | ✗ |

*scoped in the controller to their own data

---

## 🏗️ Application Architecture

### Directory Structure
```
bookland/
├── app/
│   ├── Http/
│   │   └── Controllers/        # Application controllers
│   ├── Models/                 # Eloquent models
│   └── ...
├── routes/
│   ├── web.php                # Main application routes
│   └── auth.php               # Authentication routes
├── resources/
│   ├── views/                 # Blade templates
│   ├── css/                   # Tailwind CSS
│   └── js/                    # Alpine.js scripts
├── config/
│   ├── app.php               # Application configuration
│   ├── database.php          # Database configuration
│   ├── cache.php             # Cache configuration
│   └── ...
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── public/
│   ├── index.php             # Entry point
│   └── ...
└── ...
```

### Key Controllers (20+)

1. **VilleController** - City management
2. **ZoneController** - Zone management
3. **QuartierController** - District management
4. **UserController** - User CRUD and role assignment
5. **CompteController** - Account management
6. **ContactController** - Contact management
7. **ProductController** - Product/Book management
8. **BssController** - Book Specimen Sheet management
9. **AdoptionController** - Book adoption management
10. **ConsignationController** - Consignment management
11. **RetourController** - Return management
12. **EffectifController** - Student count tracking
13. **ExamenController** - Examination management
14. **FormationController** - Training management
15. **EventController** - Event management
16. **ActionController** - Action items management
17. **ActionAmeliorationController** - Improvement actions
18. **TacheController** - Task management
19. **DemandeSpecimenController** - Specimen requests
20. **DashboardController** - Dashboard statistics
21. **NotificationController** - Notification management
22. **ReclamationController** - Complaint management
23. **NonConformiteController** - Non-conformity management
24. **VacationController** - Maintenance scheduling
25. **AgendaController** - Calendar management

---

## 🔄 Main Workflows

### Adoption Workflow
1. Create/Import products (Admin)
2. Create consignation with products (Admin/Délégué)
3. Create BSS records from consignation (Admin/Délégué/RBO)
4. Convert BSS to adoption or create manual adoption (Admin/Délégué)
5. View adoption history and print reports

### Action Management Workflow
1. Admin/RBO creates action item
2. Delegates work on action
3. Mark as "réalisée" (completed)
4. Validate action
5. Track in follow-up system

### Request Approval Workflow
1. Create specimen request (Délégué)
2. Request validation (Admin)
3. Fulfill request (Admin)
4. Print delivery confirmation

---

## 🛠️ Technologies & Dependencies

### Core Framework
- **Laravel** - Web application framework
- **Breeze** - Authentication scaffolding

### Frontend
- **Blade** - Template engine (82.2% of codebase)
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework

### Database
- **PostgreSQL** or **MySQL** - Data persistence
- **Eloquent ORM** - Database abstraction

### Additional Features
- **Artisan CLI** - Command-line interface
- **Laravel Migrations** - Database version control
- **Cache System** - Performance optimization
- **Notifications API** - Real-time notifications

---

## 📡 Key API Endpoints

### Data APIs
- `GET /api/comptes/{compte}/contacts` - Get contacts for account
- `GET /api/comptes/{compte}/bss` - Get BSS records for account
- `GET /api/comptes/{compte}/effectif` - Get student counts
- `GET /api/comptes/{compte}/niveaux` - Get academic levels
- `GET /api/comptes/{compte}/details` - Get account details
- `GET /api/villes/{ville}/zones` - Get zones for city
- `GET /api/villes/{ville}/rbos` - Get RBOs for city
- `GET /api/events/contacts-by-city` - Get contacts by city
- `GET /api/action-types-by-categorie` - Get action categories
- `GET /api/moyens-by-action-type` - Get resources for action type

### Dashboard APIs
- `GET /api/dashboard/stats` - Get dashboard statistics

### Notification APIs
- `GET /api/notifications` - List notifications
- `POST /api/notifications/{id}/read` - Mark as read
- `POST /api/notifications/read-all` - Mark all as read
- `GET /api/notifications/unread-count` - Get unread count

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.0+
- Composer
- Node.js & npm
- PostgreSQL/MySQL database

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Praydos/PFE.git
   cd PFE/bookland
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** in `.env`
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=bookland
   DB_USERNAME=your_user
   DB_PASSWORD=your_password
   ```

5. **Install front-end dependencies**
   ```bash
   npm install
   npm run build
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Start development server**
   ```bash
   php artisan serve
   npm run dev
   ```

9. **Access application**
   - URL: `http://localhost:8000`
   - Login with demo credentials (if seeded)

---

## 📝 Configuration Files

- **config/app.php** - Application settings
- **config/database.php** - Database connections
- **config/cache.php** - Cache configuration
- **tailwind.config.js** - Tailwind CSS configuration
- **.env** - Environment variables

---

## 🗄️ Database Models

Key Eloquent models included:
- User
- Compte
- Contact
- Product
- Bss
- Adoption
- Effectif
- Examen
- Formation
- Event
- Action
- Tache
- Vacation
- DemandeSpecimen
- Reclamation
- NonConformite
- And more...

---

## 🔒 Security Features

- Laravel Breeze authentication
- Role-based authorization middleware
- CSRF protection
- Password hashing
- Environment-based configuration
- Secure session handling

---

## 📊 Audit & Compliance

- Comprehensive logging system
- Non-conformity tracking
- Improvement action follow-up
- Complaint management
- Efficacy assessments
- PDF report generation

---

## 📱 Responsive Design

- Mobile-friendly interface
- Responsive layout using Tailwind CSS
- Mobile sidebar navigation
- Touch-friendly controls
- Cross-browser compatibility

---

## 🤝 Contributing

This is a finalized academic project. For modifications or improvements:
1. Create a new branch
2. Make your changes
3. Submit a pull request with description

---

## 📄 License

This project is part of an academic curriculum and is available as-is for educational purposes.

---

## 📧 Support & Contact

For questions or support regarding Bookland, please contact the project author:
- **Author**: Praydos
- **Repository**: https://github.com/Praydos/PFE

---

## 🎓 Academic Project Information

**Project Name**: Bookland - Educational Management System
**Type**: PFE (Projet de Fin d'Études - Final Year Project)
**Language Composition**: 
- Blade: 82.2%
- PHP: 17.8%

---

## 📚 Key Features Summary

✅ Multi-role user management  
✅ Geographic hierarchy (Cities → Zones → Districts)  
✅ Complete product/book catalog management  
✅ Book adoption workflow  
✅ Educational metrics tracking (effectifs, exams)  
✅ Training and event management  
✅ Quality compliance tracking  
✅ Task and action management  
✅ Complaint and non-conformity management  
✅ Calendar/agenda integration  
✅ Real-time notifications  
✅ PDF report generation  
✅ Dashboard with statistics  
✅ Comprehensive audit logging  
✅ Role-based access control  

---

**Last Updated**: June 2026
