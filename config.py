
from flask import Flask, render_template

app = Flask(__name__)

# Home 
@app.route('/')
def home():
    return render_template('homepage.html')


# Products
@app.route('/products')
def products():
    return render_template('products.html')


# Cart
@app.route('/cart')
def cart():
    return render_template('cart.html')