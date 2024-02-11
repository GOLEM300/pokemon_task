<div class="container">
    <div class="content">
        <div class="pokemon_cards">
            <div class="pokemon_card" id="pokemon_1"></div>
            <div class="pokemon_card" id="pokemon_2"></div>
            <div class="pokemon_card" id="pokemon_3"></div>
            <div class="pokemon_card" id="pokemon_4"></div>
            <div class="pokemon_card" id="pokemon_5"></div>
        </div>
        <div class="control_panel">
            <div class="generation">
                <select class="generation__select" id="generation">
                    <option class="generation__option" value="1">Поколение 1</option>
                    <option class="generation__option" value="2">Поколение 2</option>
                    <option class="generation__option" value="3">Поколение 3</option>
                    <option class="generation__option" value="4">Поколение 4</option>
                    <option class="generation__option" value="5">Поколение 5</option>
                    <option class="generation__option" value="6">Поколение 6</option>
                    <option class="generation__option" value="7">Поколение 7</option>
                    <option class="generation__option" value="8">Поколение 8</option>
                    <option class="generation__option" value="9">Поколение 9</option>
                </select>
            </div>
            <div class="control_panel__buttons">
                <button class="control_panel__button" onclick="fetchPokemons()">Найти покемонов</button>
                <button class="control_panel__button" onclick="sortPokemons()">Сортировать</button>
            </div>
        </div>

    </div>
</div>


<style>
    body {
        background-color: #d1e5d9 !important;
        background-image: url(https://ahost2.bulbagarden.net/content/bulbagarden/images/bg-green-2x-optim.png), linear-gradient(180deg, #e5f1ec 0%, #e5f1ec 322px, #d1e5d9 322px) !important;
        background-repeat: no-repeat !important;
        background-position: center 83px, 0 0 !important;
        background-size: 2250px, auto !important;
        font-family: sans-serif;
    }

    .pokemon_cards::-webkit-scrollbar {
        width: 8px;
    }

    .pokemon_cards::-webkit-scrollbar-track {
        background-color: #f0f0f0;
    }

    .pokemon_cards::-webkit-scrollbar-thumb {
        background-color: #999999;
        border-radius: 4px;
    }

    .pokemon_cards::-webkit-scrollbar-thumb:hover {
        background-color: #777777;
    }

    .container {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .content {
        border: #4CAF50;
        border-radius: 2px;
        display: flex;
        justify-content: space-between;
        width: 800px;
        margin: 20px;
    }

    .pokemon_cards {
        border: 2px solid #682A68;
        background-color: #78C850;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow-y: auto;
        height: 400px;
        width: 320px;
        overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: #999999 #f0f0f0;
    }

    .pokemon_card {
        background-color: #A7DB8D;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 300px;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding-bottom: 10px;
    }

    .pokemon_card:last-child {
        margin-bottom: 0px
    }

    .pokemon_info {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .pokemon_card__pokemon_img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        background-color: #e5f1ec;
        margin: 20px;
    }

    .control_panel {
        border: 2px solid #682A68;
        background-color: #78C850;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        overflow: hidden;
        width: 360px;
        height: 160px;
        padding: 20px;
    }

    .pokemon_types {
        display: flex;
        flex-direction: row;
    }

    .pokemon_info__pokemon_id {
        font-size: 18px;
        font-weight: bold;
    }

    .pokemon_types__pokemon_type {
        font-size: 16px;
    }

    .pokemon_abilities__pokemon_ability {
        font-size: 16px;
    }

    .generation {
        display: flex;
        align-items: flex-start;
    }

    .generation__select {
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
        color: #333;
        width: 120px;
        height: 38px;
    }

    .generation__option {
        text-align-last: center;
        background-color: #fff;
        color: #333;
    }

    .generation__option:hover {
        background-color: #f5f5f5;
    }

    .control_panel__buttons {
        display: flex;
        flex-direction: column;
        height: 96px;
        justify-content: space-between;
    }

    .control_panel__button {
        background-color: #ffcc00;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .control_panel__button:hover {
        background-color: #ff9900;
    }

    .control_panel__button:active {
        background-color: #ff6600;
    }

</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        fetchPokemons()
    })

    /**
     * Сортировка
     */
    function sortPokemons() {
        const pokemon_cards = document.querySelector('.pokemon_cards');
        let elements = pokemon_cards.children;

        for (let i = elements.length - 1; i >= 0; i--) {
            pokemon_cards.appendChild(elements[i]);
        }
    }

    /**
     * Получаем список из 5 случайных покемонов
     * Для выбранного поколения
     * @returns {Promise<void>}
     */
    const fetchPokemons = async () => {
        const generation = document.getElementById('generation').value

        try {
            const response = await fetch(`https://pokeapi.co/api/v2/generation/${generation}`)
            const data = await response.json()

            const pokemons = data.pokemon_species.slice(0, 5).sort((a, b) => {
                const first = parseInt(a.url.split('pokemon-species/')[1]);
                const last = parseInt(b.url.split('pokemon-species/')[1]);

                return first - last;
            });

            if (pokemons.length > 0) {
                pokemons.forEach((pokemon, i) => {
                    getPokemon(pokemon.name, i + 1)
                })
            } else {
                console.error('Empty data')
            }

        } catch (error) {
            console.error(`Fetch error for fetchRandomPokemons(): ${error.message}`);
            throw error
        }
    }

    /**
     * Берем покемона по его имени
     * @param pokemon_name
     * @param index
     * @returns {Promise<void>}
     */
    const getPokemon = async (pokemon_name, index) => {
        try {
            const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${pokemon_name}`)
            const pokemon = await response.json()
            const parent_element = document.getElementById(`pokemon_${index}`);

            if (Object.keys(pokemon).length !== 0) {
                pokemon_card = drawPokemon(pokemon, index)

                parent_element.parentNode.replaceChild(pokemon_card, parent_element);

            } else {
                console.error(`Empty data for : ${pokemon_name} `)
            }

        } catch (error) {
            console.error(`Fetch error for getPokemon: ${error.message}`);
            throw error
        }
    }

    /**
     * Карточка покемона
     * @param pokemon
     * @param index
     * @returns {HTMLDivElement}
     */
    function drawPokemon(pokemon, index) {
        const pokemon_card = document.createElement('div')
        pokemon_card.classList.add('pokemon_card')
        pokemon_card.id = `pokemon_${index}`

        const pokemon_info = document.createElement('div')
        pokemon_info.classList.add('pokemon_info')

        const pokemon_id = document.createElement('span')
        pokemon_id.classList.add('pokemon_info__pokemon_id')
        pokemon_id.textContent = 'ID: ' + pokemon.id

        const pokemon_img = document.createElement('img')
        pokemon_img.classList.add('pokemon_card__pokemon_img')
        pokemon_img.src = pokemon.sprites.front_default

        const pokemon_name = document.createElement('span')
        pokemon_name.classList.add('pokemon_info__pokemon_name')
        pokemon_name.textContent = 'Имя: ' + pokemon.name.charAt(0).toUpperCase() + pokemon.name.slice(1)

        pokemon_info.appendChild(pokemon_img)
        pokemon_info.appendChild(pokemon_id)
        pokemon_info.appendChild(pokemon_name)

        pokemon_card.appendChild(pokemon_info)

        pokemon_card.appendChild(drawPokemonAbilities(pokemon))

        pokemon_card.appendChild(drawPokemonTypes(pokemon))

        return pokemon_card
    }

    /**
     * Способности покемона
     * @param pokemon
     * @returns {HTMLSpanElement}
     */
    function drawPokemonAbilities(pokemon) {
        const pokemon_abilities = document.createElement('div')
        pokemon_abilities.classList.add('pokemon_abilities')

        let abilities_array = []

        for (const ability of pokemon.abilities) {
            abilities_array.push(ability.ability.name)
        }

        const pokemon_ability = document.createElement('span')
        pokemon_ability.classList.add('pokemon_abilities__pokemon_ability')
        pokemon_ability.textContent = 'Способности: ' + abilities_array.toString()

        return pokemon_abilities.appendChild(pokemon_ability)
    }

    /**
     * Тип покемона
     * @param pokemon￼
     * @returns {HTMLSpanElement}
     */
    function drawPokemonTypes(pokemon) {
        const pokemon_types = document.createElement('div')
        pokemon_types.classList.add('pokemon_types')

        let types_array = []

        for (const type of pokemon.types) {
            types_array.push(type.type.name)
        }

        const pokemon_type = document.createElement('span')
        pokemon_type.classList.add('pokemon_types__pokemon_type')
        pokemon_type.textContent = 'Тип: ' + types_array.toString()

        return pokemon_types.appendChild(pokemon_type)
    }


</script>
