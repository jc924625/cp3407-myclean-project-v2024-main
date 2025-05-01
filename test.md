# Testing – MyClean Solo Project

This page documents the testing processes used to validate core features in the MyClean application.

---

## 1. Functional Testing

| Feature                        | Test Case Description                                  | Result   |
|-------------------------------|---------------------------------------------------------|----------|
| Registration/Login            | User can sign up, log in, and access the correct dashboard | ✅ Pass |
| Booking Creation              | Customer can successfully submit a booking form        | ✅ Pass |
| Dashboard Role Redirection    | Users are redirected to appropriate dashboard after login | ✅ Pass |
| View Bookings                 | Bookings are correctly shown for the logged-in customer | ✅ Pass |
| Cleaner Booking View          | Cleaner dashboard displays all assigned bookings        | ✅ Pass |
| Profile Editing (Cleaner)     | Cleaners can update availability and details            | ✅ Pass |


---

## 2. Manual Testing Notes

All features were manually tested using mock data in the deployed version of the app. Screenshots of each test scenario are available under the `/images/iteration1_completion_evidence/` and `/images/iteration2_completion_evidence/` folders.

---

## 3. Known Issues

- Responsive layout on mobile may need improvement.
- Role management relies on frontend logic and should be secured further in production versions.

