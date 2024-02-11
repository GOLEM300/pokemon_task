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
                <button class="generation_button" onclick="fetchPokemons()">Найти покемонов</button>
            </div>
            <button class="control_panel__sort_button" onclick="sortPokemons()">Сортировать</button>
        </div>

    </div>
</div>


<style>
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
        width: 640px;
        align-items: flex-start;
    }

    .pokemon_cards {
        background-color: #d668dc;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow-y: auto;
        height: 320px;
        width: 200px;
        overflow-x: hidden;
    }

    .pokemon_card {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 200px;
        height: 240px;
        border-radius: 8px;
        object-fit: cover;
        background-color: #e8dec2;
        margin: 10px;
    }

    .pokemon_info {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .pokemon_card__pokemon_img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        background-color: #644d10;
        margin-bottom: 20px;
    }

    .control_panel {
        background-color: #4CAF50;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        overflow: auto;
        width: 240px;
        height: 96px;
    }

    .pokemon_abilities__pokemon_ability {
        margin: 0px 4px 0px 4px;
    }

    .pokemon_types {
        display: flex;
        flex-direction: row;
    }

    .pokemon_types__pokemon_type {
        margin: 0px 4px 0px 4px;
    }

</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        fetchPokemons()
    })

    function sortPokemons() {
        const pokemon_cards = document.querySelector('.pokemon_cards');
        let elements = pokemon_cards.children;

        for(let i = elements.length - 1; i>=0; i--) {
            pokemon_cards.appendChild(elements[i]);
        }
    }

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

        const pokemon_abilities = document.createElement('div')
        pokemon_abilities.classList.add('pokemon_abilities')

        for (const ability of pokemon.abilities) {
            const pokemon_ability = document.createElement('span')
            pokemon_ability.classList.add('pokemon_abilities__pokemon_ability')
            pokemon_ability.textContent = ability.ability.name

            pokemon_abilities.appendChild(pokemon_ability)
        }

        pokemon_card.appendChild(pokemon_abilities)

        const pokemon_types = document.createElement('div')
        pokemon_types.classList.add('pokemon_types')

        for (const type of pokemon.types) {
            const pokemon_type = document.createElement('span')
            pokemon_type.classList.add('pokemon_types__pokemon_type')
            pokemon_type.textContent = type.type.name

            pokemon_types.appendChild(pokemon_type)
        }

        pokemon_card.appendChild(pokemon_types)

        return pokemon_card
    }


</script>
