# DIU Movie Ticket Management

----------

## Overview

The **DIU Movie Ticket Management** project is a web-based application designed to simplify the process of booking movie tickets. It features two separate interfaces:

-   **User Interface:**
    
    -   Allows visitors to view upcoming movies, search by movie name, and book tickets easily.
        
    -   Users can sign up, log in, and track their booked tickets.
        
-   **Admin Interface:**
    
    -   Enables theater administrators to manage movies by adding, updating, or deleting entries.
        
    -   Provides an overview of current bookings and user details.
        
    -   Ensures that ticket availability is accurately maintained (for example, a user cannot book more tickets than available).
        

This project is built with standard web technologies (HTML, CSS, PHP, MySQL) and runs on the XAMPP environment for ease of development and deployment.

----------

## Key Features

-   **Easy Movie Browsing:**
    
    -   View a list of upcoming movies with details such as movie name, available seats, show date/time (formatted in AM/PM), and movie pictures.
        
    -   Movies that have ended (their showtime has passed) are automatically removed from the listings.
        
-   **User-friendly Ticket Booking:**
    
    -   Users can register and log in to book tickets.
        
    -   The system prevents overbooking by checking the available seat count in real time.
        
    -   If a user books tickets for the same movie more than once, the new tickets are added to the existing booking.
        
-   **Admin Management:**
    
    -   Admins can add movies using an easy-to-use form, including the ability to upload a movie picture which is automatically resized to **120px x 120px**.
        
    -   **Automatic Image Cleanup:** Unused movie pictures are automatically removed from the server whenever the homepage is accessed, reducing storage requirements.
        
    -   The admin dashboard displays movies, current bookings (with sequential numbering), and user information.
        
    -   Admins can cancel bookings and adjust movie details, which dynamically update the available seat count.
        
-   **Responsive & Modern UI:**
    
    -   The application uses a modern design that is consistent across all pages.
        
    -   The interface is responsive and works well on various screen sizes.
        
    -   Clear error messages and feedback help users and admins understand what actions are needed.
        

----------

## Technologies Used

-   **Frontend:**
    
    -   HTML5, CSS3
        
    -   Custom responsive design using media queries
        
-   **Backend:**
    
    -   PHP (using PDO for secure database access)
        
    -   PHP GD Library for image processing (resizing uploaded movie pictures)
        
-   **Database:**
    
    -   MySQL, managed via XAMPP
        
-   **Additional Feature:**
    
    -   **Automated Cleanup of Unused Images:** A PHP script integrated into the application checks the images folder against the movie records in the database and removes any images not in use. This helps reduce storage bloat.
        
-   **Development Environment:**
    
    -   XAMPP
        

----------

## Installation and Setup

Follow these steps to run the project locally:

1.  **Prerequisites:**
    
    -   XAMPP installed on your machine.
        
    -   Basic understanding of running Apache/MySQL from the XAMPP Control Panel.
        
    -   Git installed (optional, for cloning the repository).
        
2.  **Clone or Download the Repository:**
    
    -   Clone the repository from GitHub, or download it as a ZIP file and extract it into your `htdocs` folder inside XAMPP.
        
3.  **Set Up the Database:**
    
    -   Create a new MySQL database (e.g., `diu_movie_ticket`).
        
    -   Import the provided SQL schema to create the tables (`movies`, `users`, `bookings`).
        
    -   **Tip:** Use [http://localhost/phpmyadmin](http://localhost/phpmyadmin) to create the database and import the SQL file.
        
4.  **Configure the Connection:**
    
    -   Open `connection.php` and update the database connection details (host, username, password, database name) as necessary.
        
5.  **Ensure the Images Folder Exists:**
    
    -   Create an `images` folder in the project root (`htdocs/diu_movie_ticket_management/images`) and ensure it is writable.
        
    -   Place a `placeholder.jpg` in this folder if you want a default image to display when a movie picture isn’t provided.
        
6.  **Start XAMPP:**
    
    -   Open the XAMPP Control Panel and start Apache and MySQL.
        
7.  **Access the Application:**
    
    -   Open your browser and navigate to [http://localhost/diu_movie_ticket_management/](http://localhost/diu_movie_ticket_management/) to see the user interface.
        
    -   For the admin panel, navigate to [http://localhost/diu_movie_ticket_management/admin/adminportal.php](http://localhost/diu_movie_ticket_management/admin/adminportal.php) and log in using the admin credentials.
        

----------

## Usage

-   **For Users:**
    
    -   **Browse Movies:** Visit the homepage to view upcoming movies (including images, available seats, and showtimes).
        
    -   **Search:** Use the search box to filter movies by name.
        
    -   **Book Tickets:** Click “Book Ticket” on the desired movie. Follow the on-screen instructions to book your tickets.
        
    -   **View Bookings:** After logging in, navigate to “My Bookings” to view your booked tickets.
        
-   **For Admins:**
    
    -   **Admin Login:** Log in using your admin credentials.
        
    -   **Manage Movies:** Add new movies (with pictures), update movie details, or delete movies using the admin dashboard.
        
    -   **Automatic Image Cleanup:** Unused movie pictures are automatically removed from the server when the homepage is accessed, reducing storage requirements.
        
    -   **View and Manage Bookings:** Review current bookings and cancel them if needed.
        

----------

## Project UI

![DIUMovieTicketManagement](https://github.com/user-attachments/assets/104e517c-ffa4-4830-b509-436eb593f4a6)


----------

## Future Enhancements

-   **Payment Integration:** Connect the system to an online payment gateway.
    
-   **Seat Selection:** Allow users to choose specific seat# DIU Movie Ticket Management

----------

## Overview

The **DIU Movie Ticket Management** project is a web-based application designed to simplify the process of booking movie tickets. It features two separate interfaces:

-   **User Interface:**
    
    -   Allows visitors to view upcoming movies, search by movie name, and book tickets easily.
        
    -   Users can sign up, log in, and track their booked tickets.
        
-   **Admin Interface:**
    
    -   Enables theater administrators to manage movies by adding, updating, or deleting entries.
        
    -   Provides an overview of current bookings and user details.
        
    -   Ensures that ticket availability is accurately maintained (for example, a user cannot book more tickets than available).
        

This project is built with standard web technologies (HTML, CSS, PHP, MySQL) and runs on the XAMPP environment for ease of development and deployment.

----------

## Key Features

-   **Easy Movie Browsing:**
    
    -   View a list of upcoming movies with details such as movie name, available seats, show date/time (formatted in AM/PM), and movie pictures.
        
    -   Movies that have ended (their showtime has passed) are automatically removed from the listings.
        
-   **User-friendly Ticket Booking:**
    
    -   Users can register and log in to book tickets.
        
    -   The system prevents overbooking by checking the available seat count in real time.
        
    -   If a user books tickets for the same movie more than once, the new tickets are added to the existing booking.
        
-   **Admin Management:**
    
    -   Admins can add movies using an easy-to-use form, including the ability to upload a movie picture which is automatically resized to **120px x 120px**.
        
    -   **Automatic Image Cleanup:** Unused movie pictures are automatically removed from the server whenever the homepage is accessed, reducing storage requirements.
        
    -   The admin dashboard displays movies, current bookings (with sequential numbering), and user information.
        
    -   Admins can cancel bookings and adjust movie details, which dynamically update the available seat count.
        
-   **Responsive & Modern UI:**
    
    -   The application uses a modern design that is consistent across all pages.
        
    -   The interface is responsive and works well on various screen sizes.
        
    -   Clear error messages and feedback help users and admins understand what actions are needed.
        

----------

## Technologies Used

-   **Frontend:**
    
    -   HTML5, CSS3
        
    -   Custom responsive design using media queries
        
-   **Backend:**
    
    -   PHP (using PDO for secure database access)
        
    -   PHP GD Library for image processing (resizing uploaded movie pictures)
        
-   **Database:**
    
    -   MySQL, managed via XAMPP
        
-   **Additional Feature:**
    
    -   **Automated Cleanup of Unused Images:** A PHP script integrated into the application checks the images folder against the movie records in the database and removes any images not in use. This helps reduce storage bloat.
        
-   **Development Environment:**
    
    -   XAMPP
        

----------

## Installation and Setup

Follow these steps to run the project locally:

1.  **Prerequisites:**
    
    -   XAMPP installed on your machine.
        
    -   Basic understanding of running Apache/MySQL from the XAMPP Control Panel.
        
    -   Git installed (optional, for cloning the repository).
        
2.  **Clone or Download the Repository:**
    
    -   Clone the repository from GitHub, or download it as a ZIP file and extract it into your `htdocs` folder inside XAMPP.
        
3.  **Set Up the Database:**
    
    -   Create a new MySQL database (e.g., `diu_movie_ticket`).
        
    -   Import the provided SQL schema to create the tables (`movies`, `users`, `bookings`).
        
    -   **Tip:** Use [http://localhost/phpmyadmin](http://localhost/phpmyadmin) to create the database and import the SQL file.
        
4.  **Configure the Connection:**
    
    -   Open `connection.php` and update the database connection details (host, username, password, database name) as necessary.
        
5.  **Ensure the Images Folder Exists:**
    
    -   Create an `images` folder in the project root (`htdocs/diu_movie_ticket_management/images`) and ensure it is writable.
        
    -   Place a `placeholder.jpg` in this folder if you want a default image to display when a movie picture isn’t provided.
        
6.  **Start XAMPP:**
    
    -   Open the XAMPP Control Panel and start Apache and MySQL.
        
7.  **Access the Application:**
    
    -   Open your browser and navigate to [http://localhost/diu_movie_ticket_management/](http://localhost/diu_movie_ticket_management/) to see the user interface.
        
    -   For the admin panel, navigate to [http://localhost/diu_movie_ticket_management/admin/adminportal.php](http://localhost/diu_movie_ticket_management/admin/adminportal.php) and log in using the admin credentials.
        

----------

## Usage

-   **For Users:**
    
    -   **Browse Movies:** Visit the homepage to view upcoming movies (including images, available seats, and showtimes).
        
    -   **Search:** Use the search box to filter movies by name.
        
    -   **Book Tickets:** Click “Book Ticket” on the desired movie. Follow the on-screen instructions to book your tickets.
        
    -   **View Bookings:** After logging in, navigate to “My Bookings” to view your booked tickets.
        
-   **For Admins:**
    
    -   **Admin Login:** Log in using your admin credentials.
        
    -   **Manage Movies:** Add new movies (with pictures), update movie details, or delete movies using the admin dashboard.
        
    -   **Automatic Image Cleanup:** Unused movie pictures are automatically removed from the server when the homepage is accessed, reducing storage requirements.
        
    -   **View and Manage Bookings:** Review current bookings and cancel them if needed.
        

----------

## Screenshots

_(Insert screenshots of key pages such as the user homepage, booking page, admin dashboard, add/update movie forms, etc.)_

----------

## Future Enhancements

-   **Payment Integration:** Connect the system to an online payment gateway.
    
-   **Seat Selection:** Allow users to choose specific seats from a seating chart.
    
-   **Mobile App:** Develop a mobile-friendly version or dedicated application.
    
-   **Enhanced Reporting:** Add advanced analytics and reporting for admins (e.g., popular movies, booking trends).
    
-   **Multi-Lingual Support:** Implement support for multiple languages.
    
-   **Advanced Image Management:** Continue optimizing image storage and processing; possibly integrate cloud storage.
    

----------

## Conclusion

The **DIU Movie Ticket Management** project provides a comprehensive solution for online movie ticket booking with dedicated interfaces for users and administrators. Key features include real-time seat availability checks, automatic image resizing to **120px x 120px**, and an innovative feature that automatically cleans up unused movie pictures to reduce storage issues. Built with HTML, CSS, PHP, and MySQL, the system features a modern, responsive UI and is designed to be both scalable and maintainable, forming a solid foundation for future enhancements.s from a seating chart.
    
-   **Mobile App:** Develop a mobile-friendly version or dedicated application.
    
-   **Enhanced Reporting:** Add advanced analytics and reporting for admins (e.g., popular movies, booking trends).
    
-   **Multi-Lingual Support:** Implement support for multiple languages.
    
-   **Advanced Image Management:** Continue optimizing image storage and processing; possibly integrate cloud storage.
    

----------

## Conclusion

The **DIU Movie Ticket Management** project provides a comprehensive solution for online movie ticket booking with dedicated interfaces for users and administrators. Key features include real-time seat availability checks, automatic image resizing to **120px x 120px**, and an innovative feature that automatically cleans up unused movie pictures to reduce storage issues. Built with HTML, CSS, PHP, and MySQL, the system features a modern, responsive UI and is designed to be both scalable and maintainable, forming a solid foundation for future enhancements.
