# Tinder-like Dating App - Backend API

A comprehensive backend API for a Tinder-like dating application built with Laravel. Features include user recommendations, swipe functionality (like/dislike), match tracking, and automated email notifications for popular users.

## 🚀 Live Demo

- **API Base URL**: `https://your-app.railway.app`
- **Swagger Documentation**: `https://your-app.railway.app/api/documentation`

## 📋 Features

- ✅ **User Recommendations**: Get paginated list of potential matches
- ✅ **Like System**: Like users with duplicate prevention
- ✅ **Dislike System**: Pass on users you're not interested in
- ✅ **Liked People List**: View all users you've liked
- ✅ **Popular User Notifications**: Automated email alerts when a user receives 50+ likes
- ✅ **Swagger Documentation**: Interactive API documentation
- ✅ **Database Relationships**: Properly structured with foreign keys
- ✅ **RESTful API Design**: Clean and intuitive endpoints

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| **Laravel 10** | PHP Framework |
| **MySQL** | Relational Database |
| **L5-Swagger** | API Documentation |
| **SMTP (Gmail)** | Email Notifications |
| **Railway** | Cloud Deployment |

## 📚 API Endpoints

### 1. Get Recommended People
GET /api/people/recommended?user_id=1&per_page=10



**Query Parameters:**
- `user_id` (optional): Current user ID (default: 1)
- `per_page` (optional): Items per page (default: 10)

**Response:**
{
"success": true,
"data": {
"current_page": 1,
"data": [
{
"id": 3,
"name": "User 3",
"age": 28,
"location": "Jakarta",
"email": "user3@test.com",
"pictures": [
{
"id": 3,
"user_id": 3,
"image_url": "https://i.pravatar.cc/300?img=3"
}
]
}
],
"per_page": 10,
"total": 60
}
}



### 2. Like a Person
POST /api/people/{id}/like?user_id=1



**Path Parameters:**
- `id`: Target user ID to like

**Response:**
{
"success": true,
"message": "Person liked successfully",
"data": {
"user_id": 1,
"target_user_id": 10,
"action": "like",
"created_at": "2025-12-04T15:50:00.000000Z"
}
}



### 3. Dislike a Person
POST /api/people/{id}/dislike?user_id=1



**Response:**
{
"success": true,
"message": "Person disliked successfully"
}



### 4. Get Liked People
GET /api/people/liked?user_id=1&per_page=10



**Response:**
{
"success": true,
"data": {
"data": [
{
"id": 67,
"user_id": 1,
"target_user_id": 10,
"action": "like",
"target_user": {
"id": 10,
"name": "User 10",
"age": 25,
"location": "Jakarta",
"pictures": [...]
}
}
]
}
}



## 🗄️ Database Schema

### Users Table
id BIGINT PRIMARY KEY
name VARCHAR(255)
age INTEGER
location VARCHAR(255)
email VARCHAR(255) UNIQUE
created_at TIMESTAMP
updated_at TIMESTAMP



### Pictures Table
id BIGINT PRIMARY KEY
user_id BIGINT FOREIGN KEY -> users.id
image_url VARCHAR(255)
created_at TIMESTAMP
updated_at TIMESTAMP



### Swipes Table
id BIGINT PRIMARY KEY
user_id BIGINT FOREIGN KEY -> users.id
target_user_id BIGINT FOREIGN KEY -> users.id
action ENUM('like', 'dislike')
created_at TIMESTAMP
updated_at TIMESTAMP

UNIQUE KEY (user_id, target_user_id)



## 🔧 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Git

### Local Setup

1. **Clone the repository**
git clone https://github.com/YOUR_USERNAME/tinder-api-backend.git
cd tinder-api-backend



2. **Install dependencies**
composer install



3. **Environment configuration**
cp .env.example .env
php artisan key:generate



4. **Configure database in `.env`**
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tinder_api
DB_USERNAME=root
DB_PASSWORD=your_password



5. **Configure email in `.env`**
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Tinder API"



6. **Create database**
mysql -u root -p
CREATE DATABASE tinder_api;
exit;



7. **Run migrations and seeders**
php artisan migrate --seed



8. **Generate Swagger documentation**
php artisan l5-swagger:generate



9. **Start development server**
php artisan serve



10. **Access the application**
- API: `http://localhost:8000/api`
- Swagger Docs: `http://localhost:8000/api/documentation`

## ⏰ Cronjob Setup

### Manual Execution
Check for users with 50+ likes and send email notifications:
php artisan check:popular-users



### Automated Scheduling
Add to your system crontab:
cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1



The command runs daily and sends emails to admins when users receive more than 50 likes.

## 🧪 Testing

### Using cURL

**Get recommendations:**
curl "http://localhost:8000/api/people/recommended?user_id=1&per_page=5"



**Like a person:**
curl -X POST "http://localhost:8000/api/people/2/like?user_id=1"



**Dislike a person:**
curl -X POST "http://localhost:8000/api/people/3/dislike?user_id=1"



**Get liked people:**
curl "http://localhost:8000/api/people/liked?user_id=1"



### Using Postman

1. Import Swagger JSON: `http://localhost:8000/api/documentation.json`
2. All endpoints will be automatically configured
3. Test each endpoint with sample data

### Test Cronjob

1. Create 50+ likes for a user:
php artisan tinker


undefined
for($i=1;$i<=55;$i++){
try {
\App\Models\Swipe::create([
'user_id' => $i,
'target_user_id' => 2,
'action' => 'like'
]);
} catch(\Exception $e) {}
}
exit;



2. Run the cronjob:
php artisan check:popular-users



3. Check email inbox for notification

## 📦 Project Structure

tinder-api-backend/
├── app/
│ ├── Console/
│ │ └── Commands/
│ │ └── CheckPopularUsers.php # Cronjob command
│ ├── Http/
│ │ └── Controllers/
│ │ └── Api/
│ │ └── PeopleController.php # API endpoints
│ └── Models/
│ ├── User.php # User model
│ ├── Picture.php # Picture model
│ └── Swipe.php # Swipe model
├── database/
│ ├── migrations/
│ │ ├── xxxx_create_users_table.php
│ │ ├── xxxx_create_pictures_table.php
│ │ └── xxxx_create_swipes_table.php
│ └── seeders/
│ ├── DatabaseSeeder.php
│ └── UserSeeder.php # Dummy data
├── routes/
│ └── api.php # API routes
├── storage/
│ └── api-docs/
│ └── api-docs.json # Generated Swagger docs
├── .env.example # Environment template
├── .gitignore
├── composer.json # PHP dependencies
├── nixpacks.toml # Railway deployment config
└── README.md # This file



## 🚀 Deployment

### Railway Deployment

1. **Push to GitHub**
git add .
git commit -m "Initial commit"
git push origin main



2. **Deploy on Railway**
- Go to https://railway.app/
- Create new project from GitHub repo
- Add MySQL database
- Configure environment variables
- Deploy automatically

3. **Post-deployment**
Railway will automatically run:
php artisan migrate --force
php artisan db:seed --force
php artisan l5-swagger:generate



### Other Deployment Options
- Heroku
- DigitalOcean App Platform
- AWS EC2
- Google Cloud Run

## 📝 Environment Variables

Required environment variables for production:

APP_NAME=TinderAPI
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"



## 🔐 Security Features

- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ Unique Constraint on Swipes (Prevent duplicate swipes)
- ✅ Foreign Key Constraints (Data integrity)
- ✅ CSRF Protection (Laravel default)
- ✅ Environment Variables (Sensitive data protection)

## 📊 Performance Considerations

- Pagination on all list endpoints
- Database indexing on foreign keys
- Eager loading for relationships (`with()`)
- Query optimization with `whereNotIn` for exclusions

## 👨‍💻 Developer

**Zalikha**
- Full-stack Developer
- Technologies: PHP, Laravel, Golang, Python, Next.js, Flutter
- Previous Experience: Wesclic Studio

## 📄 License

This project was created for technical assessment purposes.

## 🤝 Contributing

This is a technical test project and is not open for contributions.

## 📞 Contact

For any questions regarding this project, please contact:
- Email: your-email@example.com
- GitHub: [@your-username](https://github.com/your-username)

---

**Built with ❤️ using Laravel**