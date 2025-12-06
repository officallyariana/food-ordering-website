document.addEventListener("DOMContentLoaded", () => {
    const orderSummary = document.getElementById("order-summary");
    const checkoutForm = document.getElementById("checkout-form");
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    let total = 0;
    const ul = document.createElement("ul");

    cart.forEach(item => {
        total += item.price * item.qty;
        const li = document.createElement("li");
        li.textContent = `${item.name} × ${item.qty} — $${(item.price * item.qty).toFixed(2)}`;
        ul.appendChild(li);
    });

    orderSummary.appendChild(ul);

    const totalP = document.createElement("p");
    totalP.textContent = `Total: $${total.toFixed(2)}`;
    orderSummary.appendChild(totalP);

    checkoutForm.addEventListener("submit", async e => {
        e.preventDefault();

        const response = await fetch("place_order.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ cart, total })
        });

        const result = await response.json();

        if (result.status === "success") {
            alert("Order placed successfully!");
            localStorage.removeItem("cart");
            window.location.href = "orders.php";
        } else {
            alert(result.message);
        }
    });
});
