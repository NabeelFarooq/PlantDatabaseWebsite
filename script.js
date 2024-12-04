document.getElementById('searchButton').addEventListener('click', function() {
    const searchTerm = document.getElementById('searchInput').value;
    fetch(`search_plants.php?q=${searchTerm}`)
        .then(response => response.json())
        .then(plants => {
            const resultsDiv = document.getElementById('plantResults');
            resultsDiv.innerHTML = '';  // Clear previous results
            
            if (plants.length === 0) {
                resultsDiv.innerHTML = "<p class='text-xl text-center text-gray-600'>No plants found. Try a different search term.</p>";
                return;
            }

            plants.forEach(plant => {
                const card = document.createElement('div');
                card.classList.add('bg-white', 'p-6', 'rounded-lg', 'shadow-lg', 'mb-6', 'hover:shadow-xl', 'transition', 'w-full', 'md:w-1/3', 'lg:w-1/4', 'mx-auto');
                card.innerHTML = `
                    <img src="${plant.image}" alt="${plant.name}" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">${plant.name}</h3>
                    <p class="text-lg text-gray-600 mb-4"><strong>Family:</strong> ${plant.family}</p>
                    <p class="text-base text-gray-500">${plant.description}</p>
                `;
                resultsDiv.appendChild(card);
            });
        })
        .catch(error => console.log('Error fetching data:', error));
});
    
document.getElementById('plantForm').addEventListener('submit', async (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);
    const responseContainer = document.getElementById('response-message');

    try {
        const response = await fetch('add_plant.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();

        if (result.success) {
            responseContainer.textContent = result.message;
            responseContainer.classList.add('text-green-500');
            responseContainer.classList.remove('text-red-500');
        } else {
            responseContainer.textContent = result.message;
            responseContainer.classList.add('text-red-500');
            responseContainer.classList.remove('text-green-500');
        }

        event.target.reset();
    } catch (error) {
        responseContainer.textContent = 'An error occurred. Please try again.';
        responseContainer.classList.add('text-red-500');
        responseContainer.classList.remove('text-green-500');
    }
});


// Function to fetch and display plants (if you want to reflect new additions dynamically)
function fetchPlants() {
    fetch('get_plants.php')
        .then(response => response.json())
        .then(plants => {
            const resultsDiv = document.getElementById('plantResults');
            resultsDiv.innerHTML = ''; // Clear previous results

            plants.forEach(plant => {
                const card = document.createElement('div');
                card.classList.add('bg-white', 'p-6', 'rounded-lg', 'shadow-lg', 'mb-6', 'hover:shadow-xl', 'transition');
                card.innerHTML = `
                    <img src="${plant.image}" alt="${plant.name}" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">${plant.name}</h3>
                    <p class="text-lg text-gray-600 mb-4"><strong>Family:</strong> ${plant.family}</p>
                    <p class="text-base text-gray-500">${plant.description}</p>
                `;
                resultsDiv.appendChild(card);
            });
        })
        .catch(error => console.error('Error fetching plants:', error));
}

              
