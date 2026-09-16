## Add Dynamic Taxi Fare Calculator Below Hero Banner

I have already developed the TaxiGo website using your SaaS website builder. The website is fully dynamic, and all content, images, taxi listings, and other information are managed through the user dashboard.

**Do not redesign or replace the existing website.** Keep the current TaxiGo theme, branding, colors, typography, navigation, hero banner, and overall UI/UX exactly as they are.

### New Functionality: Taxi Fare Calculator

Add a new, attractive, professional, and fully responsive taxi fare calculator section immediately below the existing hero banner.

The new section should visually match the TaxiGo theme, using the existing yellow, white, and dark color palette.

### Customer-Side Features

1. Pickup Location: Allow customers to select or enter their pickup location.
2. Drop-off Location: Allow customers to select or enter their destination.
3. Distance Calculation: Automatically calculate the travel distance in kilometers between the pickup and drop-off locations using a reliable map or distance calculation service.
4. Taxi Selection: Allow customers to choose from the available taxi or vehicle types configured in the dashboard.
5. Dynamic Pricing: Calculate the estimated fare based on the selected vehicle's kilometer-wise pricing.
6. Fare Display: Show the estimated distance, selected vehicle, price per kilometer, and total estimated fare in a clear and attractive layout.
7. Booking Action: Include a “Book This Ride” or “Continue Booking” button that passes the selected pickup, drop-off, vehicle, distance, and estimated fare to the booking process.

### Admin Dashboard Integration

The fare calculator must be connected to the existing user dashboard and database.

Add backend configuration options specifically for this TaxiGo theme:

* Add, edit, and delete taxi/vehicle types.
* Set a separate price per kilometer for each vehicle.
* Configure base fare, if required.
* Configure additional charges, if required.
* Enable or disable vehicles from appearing in the calculator.
* Update pricing dynamically whenever the admin changes the settings.

Example:

| Vehicle Type | Price per km |
| ------------ | ------------ |
| Sedan        | ₹20/km       |
| SUV          | ₹30/km       |
| Premium      | ₹50/km       |

These are illustrative values only. The admin must be able to enter their own prices through the dashboard.

### Dynamic Data Requirements

* Do not hardcode taxi listings, prices, or vehicle details in the frontend.
* Fetch all taxi and pricing data from the existing dashboard/database.
* Ensure that any changes made by the admin are reflected automatically on the live website.
* Follow the existing dashboard's data structure and authentication system.
* Do not break any existing website functionality.

### UI/UX Requirements

* Place the calculator directly below the hero banner.
* Use a modern, premium taxi-booking interface.
* Maintain the existing TaxiGo branding and visual style.
* Ensure the section is mobile-friendly and responsive.
* Add smooth interactions, clear input fields, loading states, and validation messages.
* Display a helpful message if the distance cannot be calculated.
* Keep the design clean and conversion-focused.

### Important

Implement this as an additional feature within the existing TaxiGo theme, not as a separate website or redesigned homepage. Preserve all existing sections and dashboard functionality.


Only for these theme and these will display for these theme on the user dashboard not for other theme user dahboard 