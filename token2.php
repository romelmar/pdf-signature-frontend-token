<script>
    async function getToken() {
        try {
            const response = await fetch('http://127.0.0.1:8000/api/generate-token');
            
            if (response.ok) {
                const data = await response.json();
                localStorage.setItem('token', data.token);
                console.log('Token generated and saved to localStorage:', data.token);
            } else {
                console.error('Failed to generate token:', response.statusText);
            }
        } catch (error) {
            console.error('Error fetching token:', error);
        }
    }

    async function fetchProtectedData() {
        const token = localStorage.getItem('token');
        if (!token) {
            console.error('No token found. Please generate a token first.');
            return;
        }

        try {
            const response = await fetch('http://127.0.0.1:8000/api/sign-pdf', {
                method: 'POST', // Use POST if the server expects it
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                console.log('Protected Data:', data);
            } else {
                console.error('Failed to fetch protected data:', response.statusText);
            }
        } catch (error) {
            console.error('Error fetching protected data:', error);
        }
    }

    // Example of getting a token and accessing protected data
    getToken().then(fetchProtectedData);
</script>
