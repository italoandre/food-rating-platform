# About this project

Hi! I am Italo, and I am glad to take part on this assessment.

## Requirements

- The library `tymon/jwt-auth` was used to create JWT tokens.
- The library `intervention/image` was used to maipulate images (create thumbnails).

## Covered scenarios
- Listing of restaurants
- Details of a restaurant, with a list of reviews
- Submission of a new review

## Code Standards

- The controllers were all single responsibility classes, which makes the project easier to read and maintain.
- I used UUIDs to identify the records, allowing data obfuscation and cross systems compatibility. However, to avoid performance issues of having _just_ UUIDs as identifiers, I chose to use UUIDs as external ids (ids that are exposed by the API) and stick to regular ids in primary and foreign keys (which should be for internal use only, never exposed by the API).  
- All endpoints were covered with tests.
- I favored trying to name methods more descriptively and breaking them down if needed over leaving code comments.

## Tests

During the development of the code on this repository, I used unit and feature/integration tests. I am familiar with TDD and tried to use it in this project. The tests are structured according to the 3 As: arrange, act and assert. During the process, I also tried to stick to the red-green-refactor steps of TDD.

As backend is my focus (going deeper in frontend is on my roadmap), I try to test the backend by itself. All features were first done in the backend, tested in the backend, then I moved to frontend development.

## Possible next steps

- Something I wish I had the time to do was to document the endpoints. My choice was going to be Swagger.

- Add authentication methods to the TestCase class, so other tests could easily inherit it and favor code reusability.

- I also really wish I could have implemented push notification or even websockets, as part of the story 002.

## Frontend

I decided to keep the frontend code in another repository, to keep both repositories cleaner and easier to navigate. As I was told the most important would be to use Laravel and that I could use something other than Vue in the frontend, I decided to use React, which I am a bit more familiar with. [Visit the frontend repository](https://github.com/italoandre/curotech-food-rating-front).
