const axios = require('axios');

async function fetchAndCombineApis(apiUrls) {
    try {
        const fetchPromises = apiUrls.map(url =>
            axios.get(url, { timeout: 5000 })
                .then(response => response.data)
                .catch(error => {
                    console.error(`Error fetching data from ${url}:`, error.message);
                    return null;
                })
        );

        const results = await Promise.allSettled(fetchPromises);

        const combinedData = results
            .filter(result => result.status === 'fulfilled' && result.value !== null)
            .map(result => result.value)
            .flat();

        return combinedData;
    } catch (error) {
        console.error('Unexpected error:', error.message);
        return [];
    }
}

(async () => {
    const apiUrls = [
        'https://api.example.com/data1',
        'https://api.example.com/data2',
        'https://api.example.com/data3',
    ];

    const combinedData = await fetchAndCombineApis(apiUrls);
    console.log('Combined Data:', combinedData);
})();