let shoppingCart = {
  items: [],
};

function addToShoppingCart(productId, amount, price, title, image) {
  let cart = getShoppingCart();

  let product = cart.items.find((obj) => obj.id === productId);
  if (product !== undefined) {
    product.amount += amount;
    setShoppingCartAmount();
    return saveShoppingCart(cart);
  }

  cart.items.push({
    id: productId,
    amount: amount,
    price: price,
    title: title,
    image: image,
  });
  setShoppingCartAmount();
  return saveShoppingCart(cart);
}

function getShoppingCart() {
  let cart = localStorage.getItem("shoppingCart");
  if (cart === undefined || cart === null || cart === "null") {
    cart = JSON.stringify(shoppingCart);
    localStorage.setItem("shoppingCart", cart);
  }
  return JSON.parse(cart);
}

function saveShoppingCart(cart) {
  return localStorage.setItem("shoppingCart", JSON.stringify(cart));
}

function getItemCount() {
  let cart = localStorage.getItem("shoppingCart");
  if (cart === undefined || cart === null || cart === "null") {
    cart = JSON.stringify(shoppingCart);
    localStorage.setItem("shoppingCart", cart);
  }
  let total = 0;
  JSON.parse(cart).items.forEach((item) => {
    total += item.amount;
  });
  return total;
}

function setShoppingCartAmount() {
  $("#shopping-cart-amount").html(getItemCount());
}

$(document).ready(function () {
  $("#shoppingCart").on("show.bs.modal", () => {
    const cart = getShoppingCart();
    $("#shopping-cart-body").html("");
    let total_price = 0;
    let html = "";
    cart.items.forEach((item) => {
      total_price += item.price * item.amount;
      html += `<tr>
          <td><img src="${item.image}" height="50"></img></td>
          <td>${item.title}</td>
          <td class="text-center">${item.amount}</td>
          <td class="text-end">${item.price}</td>
        </tr>`;
    });
    $("#shopping-cart-body").html(html);
    $("#shopping-cart-total-price").html(total_price);
  });
});
