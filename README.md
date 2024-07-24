This is a small system of a restaurant with random food offering 6 different recipes. The ingredients are used up as the dishes are served. The system works with two Docker containers: one for serving the dishes and one for the kitchen.

Manager Container:

The container that contains the restaurant view is the manager container and is accessed from the URL:
http://127.0.0.1:8001
This container shows the available recipes, the current ingredients and the history of dishes served.

Kitchen Container:

The kitchen is operated from the URL:
http://127.0.0.1:8002
This container handles the logic of purchasing ingredients and preparing dishes.

Features
 -Random recipes: The system offers 6 different recipes with random ingredients.
 
 -Ingredient depletion: As dishes are served, ingredients run out.
 
 -Purchase of ingredients: It is possible to purchase additional ingredients from the kitchen.
 
 -Dish history: A record is kept of the dishes served, sorted by date.
 

This system is designed to simulate the operation of a restaurant with randomized food and ingredient management, using two separate Docker containers for better organization and scalability.

contains a docker compose file that can be run with the command
docker compose up
