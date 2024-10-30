<button onclick="login()">Login</button>
<button onclick="fetchProtectedData()">fetchData</button>
<button onclick="logout()">Logout</button>

<script>
    async function login() {
        const email = 'romelmar.alejandrino@gmail.com';
        const password = 'password';

        const response = await fetch('http://127.0.0.1:8000/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email,
                password
            }),
        });

        const data = await response.json();


        if (response.ok) {
            // localStorage.setItem('token_esign', data.token);


            setItemWithExpiration('token_esign', data.token, 5); // Set an item with a 4hors expiration
            console.log('Logged in successfully');
        } else {
            alert('Login failed');
        }
    }

    function fetchProtectedData() {
        const token = getItemWithExpiration('token_esign'); // Retrieve the token

        fetch('http://127.0.0.1:8000/api/sign-pdf', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`, // Include the token in the Authorization header
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => console.log('Protected Data:', data))
            .catch(error => console.error('Error:', error));
    }

    // Call the function to fetch protected data after login

    function logout() {
        fetch('http://127.0.0.1:8000/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${getItemWithExpiration('token_esign')}`,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    localStorage.removeItem('token_esign'); // Clear the token
                    console.log('Logged out successfully');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function setItemWithExpiration(key, value, expirationInMinutes) {
        const now = new Date();
        const item = {
            value: value,
            expiry: now.getTime() + expirationInMinutes * 60 * 1000, // Expiry time in milliseconds
        };
        localStorage.setItem(key, JSON.stringify(item));
    }

    function getItemWithExpiration(key) {
        const itemStr = localStorage.getItem(key);
        // If the item doesn't exist, return null
        if (!itemStr) {
            return null;
        }
        const item = JSON.parse(itemStr);
        const now = new Date();

        // Compare the expiry time
        if (now.getTime() > item.expiry) {
            // If expired, remove it from local storage and return null
            localStorage.removeItem(key);
            return null;
        }
        return item.value;
    }
</script>