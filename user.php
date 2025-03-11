<?php
// user.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DEEPIKA RESTAURANT - Menu</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-sky-200 to-sky-400">
  <!-- Header -->
  <header class="bg-sky-600 shadow-xl fixed top-0 left-0 right-0 z-10">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <div class="text-xl font-bold text-white">DEEPIKA RESTAURANT</div>
      <div class="flex items-center space-x-4">
        <!-- Bowl Button -->
        <button onclick="showMyBowl()" class="text-white hover:text-gray-200 relative">
          My Bowl
          <span id="bowl-notification" class="absolute top-0 -right-3 text-xs bg-red-600 text-white rounded-full px-1">0</span>
        </button>
      </div>
    </div>
  </header>
  <!-- Main Content -->
  <main class="pt-20 container mx-auto px-6">
    <!-- Categories -->
    <div id="menu-categories" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-gray-100 p-4 rounded-lg shadow-xl hover:shadow-2xl transition transform hover:scale-105 cursor-pointer" onclick="showItems('chef-choice')">Chef Choice Specials</div>
      <div class="bg-gray-100 p-4 rounded-lg shadow-xl hover:shadow-2xl transition transform hover:scale-105 cursor-pointer" onclick="showItems('starters-veg')">Starters - Veg</div>
      <div class="bg-gray-100 p-4 rounded-lg shadow-xl hover:shadow-2xl transition transform hover:scale-105 cursor-pointer" onclick="showItems('salads')">Salads</div>
      <div class="bg-gray-100 p-4 rounded-lg shadow-xl hover:shadow-2xl transition transform hover:scale-105 cursor-pointer" onclick="showItems('soups-veg')">Soups - Veg</div>
      <div class="bg-gray-100 p-4 rounded-lg shadow-xl hover:shadow-2xl transition transform hover:scale-105 cursor-pointer" onclick="showItems('sea-food')">Sea Food</div>
    </div>
    <!-- for the selcting-->
    <div id="items-container" class="hidden mt-8">
      <button onclick="backToMenu()" class="mb-6 bg-gradient-to-r from-neutral-600 to-neutral-950 text-white px-6 py-3 rounded-full hover:from-neutal-500 hover:to-neutral-700">Back to Menu</button>
      <div id="items-list"></div>
    </div>
    <!-- in the my bowl-->
    <div id="bowl-container" class="hidden mt-8">
      <button onclick="backToMenu()" class="mb-6 bg-gradient-to-r from-neutral-600 to-neutral-950 text-white px-6 py-3 rounded-full hover:from-neutral-500 hover:to-neutral-700">Back to Menu</button>
      <h2 class="text-3xl font-bold mb-6 text-center">Your Items: </h2>
      <div id="bowl-items"></div>
      <div id="bowl-summary" class="mt-6 p-6 bg-white rounded-lg shadow-lg"></div>
      <button onclick="placeOrder()" class="mt-6 w-full bg-gradient-to-r from-green-500 to-green-700 text-white px-6 py-3 rounded-full hover:from-green-400 hover:to-green-600">Place Order</button>
      <div id="empty-bowl-message" class="mt-4 text-red-600 font-bold hidden">You cannot place an order without any items!</div>
      <div id="order-success-message" class="mt-4 text-green-600 font-bold hidden">Order placed successfully! Your Order ID is <span id="order-id"></span>.</div>
    </div>
  </main>

  <script>
    var categories = {
      "chef-choice": [
        { name: "Koramenu Fish Tandoori", price: 800 },
        { name: "Pomfret Fish Tandoori", price: 700 }
      ],
      "starters-veg": [
        { name: "Veg Manchurian", price: 140 },
        { name: "Gobi Manchurian", price: 130 },
        { name: "Crispy Vegetables", price: 140 }
      ],
      "salads": [
        { name: "Green Salad", price: 60 },
        { name: "Fruit Salad", price: 80 },
        { name: "Cucumber Salad", price: 60 }
      ],
      "soups-veg": [
        { name: "Tomato Soup", price: 80 },
        { name: "Sweet Corn Soup", price: 80 }
      ],
      "sea-food": [
        { name: "Fish Fry", price: 250 },
        { name: "Apollo Fish", price: 275 },
        { name: "Chilly Fish", price: 275 }
      ]
    };

    var bowl = [];
    var notificationCount = 0;
    // for showing the items
    function showItems(category) {
      var items = categories[category];
      var itemsContainer = document.getElementById("items-container");
      var menuCategories = document.getElementById("menu-categories");
      var itemsList = document.getElementById("items-list");

      menuCategories.classList.add("hidden");
      itemsContainer.classList.remove("hidden");
      itemsList.innerHTML = "";
      for (var i = 0; i < items.length; i++) {
        var itemDiv = document.createElement("div");
        itemDiv.className = "flex justify-between items-center bg-white p-4 rounded-lg shadow mb-4";

        var itemName = document.createElement("span");
        itemName.textContent = items[i].name + " - ₹" + items[i].price;

        var addButton = document.createElement("button");
        addButton.className = "bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600";
        addButton.textContent = "Add to Bowl";
        addButton.setAttribute("data-name", items[i].name);
        addButton.setAttribute("data-price", items[i].price);
        addButton.onclick = addToBowl;

        itemDiv.appendChild(itemName);
        itemDiv.appendChild(addButton);
        itemsList.appendChild(itemDiv);
      }
    }

    function addToBowl() {
      var itemName = this.getAttribute("data-name");
      var itemPrice = parseInt(this.getAttribute("data-price"));
      var found = false;

      for (var i = 0; i < bowl.length; i++) {
        if (bowl[i].name === itemName) {
          bowl[i].quantity++;
          found = true;
          break;
        }
      }

      if (!found) {
        bowl.push({ name: itemName, price: itemPrice, quantity: 1 });
      }

      notificationCount++;
      document.getElementById("bowl-notification").textContent = notificationCount;
    }

    function showMyBowl() {
      var bowlContainer = document.getElementById("bowl-container");
      var menuCategories = document.getElementById("menu-categories");
      var itemsContainer = document.getElementById("items-container");
      var bowlItems = document.getElementById("bowl-items");
      var bowlSummary = document.getElementById("bowl-summary");

      menuCategories.classList.add("hidden");
      itemsContainer.classList.add("hidden");
      bowlContainer.classList.remove("hidden");
      bowlItems.innerHTML = "";

      var totalCost = 0;

      for (var i = 0; i < bowl.length; i++) {
        var itemDiv = document.createElement("div");
        itemDiv.className = "flex justify-between items-center bg-white p-4 rounded-lg shadow mb-4";

        var itemName = document.createElement("span");
        itemName.textContent = bowl[i].name + " (x" + bowl[i].quantity+")";

        var removeButton = document.createElement("button");
        removeButton.className = "bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600";
        removeButton.textContent = `Remove ${bowl[i].quantity-1}`;
        removeButton.setAttribute("data-index", i);
        removeButton.onclick = removeOneItem;
        if(removeButton.textContent=="Remove 0"){
          removeButton.textContent = `Remove `;
        }

        itemDiv.appendChild(itemName);
        itemDiv.appendChild(removeButton);
        bowlItems.appendChild(itemDiv);

        totalCost += bowl[i].price * bowl[i].quantity;
      }

      var gst = totalCost * 0.05;
      var finalCost = totalCost + gst;

      bowlSummary.innerHTML = `
        <div>
          <div class="mb-2"><strong>Total:</strong> ₹${totalCost}</div>
          <div class="mb-2"><strong>GST (5%):</strong> ₹${gst}</div>
          <div class="text-lg"><strong>Final Total:</strong> ₹${finalCost}</div>
        </div>
      `;
    }

    function removeOneItem() {
      var index = parseInt(this.getAttribute("data-index"));
      bowl[index].quantity--;

      notificationCount--;
      if (bowl[index].quantity === 0) {
        bowl.splice(index, 1);
      }

      document.getElementById("bowl-notification").textContent = notificationCount;
      showMyBowl();
    }

    function backToMenu() {
      document.getElementById("menu-categories").classList.remove("hidden");
      document.getElementById("items-container").classList.add("hidden");
      document.getElementById("bowl-container").classList.add("hidden");
    }
    function placeOrder() {
  if (bowl.length === 0) {
    document.getElementById("empty-bowl-message").classList.remove("hidden");
    return;
  }

  document.getElementById("empty-bowl-message").classList.add("hidden");

  fetch("place_order.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ items: bowl })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Clear the bowl
        bowl = [];
        notificationCount = 0;
        document.getElementById("bowl-notification").textContent = notificationCount;

        // Hide the bowl container
        document.getElementById("bowl-container").classList.add("hidden");

        // Display the order ID and success message
        const mainContainer = document.querySelector("main");
        mainContainer.innerHTML = `
          <div class="text-center mt-20">
            <h1 class="text-4xl font-bold text-green-600 mb-4">Your Order ID: ${data.orderId}</h1>
            <p class="text-xl text-gray-700">Your order has been placed successfully!</p>
            <button class="mt-8 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick=backToMenu()>
              Order Again
            </button>
          </div>
        `;
      } else {
        console.error("Order placement failed:", data.message);
      }
    })
    .catch(err => console.error("Error placing order:", err));
}
  </script>
</body>
</html>
