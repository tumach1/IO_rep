class BookInfo
{
    id = 0;
    title = "No Name";
    subtitle = "No subtitle";
    title_original = "";
    subtitle_original = "";
    description = "No description";
    pages = 0;
    year = 1900;
    month = 12;
    day = 1;
    authors = ["First Author", "Second Author"];
    rating = 100;
    genres = ["genre 1", "genre 2"];
    imageUrl = "";
    copies = 0;
    binding = "";
    isbn = 0;
    location = "Knowhere";    
    publisher = "Who knows"

    isRecommended = false;
    isRecentRead = false;
}

async function createOrder(user_id) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert('Cart is empty');
        return;
    }
    const order = {
        user_id: user_id,
        bookIds: cart.map(book => book.id),
    };
    console.log(JSON.stringify(order));
    try {
        const response = await fetch('/order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(order),
        });
        console.log(await response.text());
        console.log("133");
        if (!response.ok) {
            // console.log(response.data);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        console.log("response", response);
        // const data = await response.json();
        // console.log(data);
        alert('Order created successfully');
        localStorage.removeItem('cart');
        updateCartView();
    } catch (error) {
        console.log(error.message);
        alert('Order creation failed');


    }
}
async function GetAllBooks()
{
    // fill this array with ANY number of [BookInfo] class objects in this method and everything else works automatically

    try
    {
        // try load data from local server
        let data = await DownloadJsonData("/books");
        books_all = ParseJsonBooks(data);
    } catch (error)
    {
        // fake data
        books_all = GetRandomBooks(100, 0, "", false, true);
    }
}

async function GetRecommendedBooks()
{
    // fill this array with 12 [BookInfo] class objects in this method and everything else works automatically
    books_recommended = []

    let i = 0;
    while (i < 12)
    {
        book = books_all[Math.floor(Math.random() * (books_all.length - 1))];
        if(books_recommended.includes(book))
            continue;

        books_recommended.push(book);
        i++;
    }
}

async function GetRecentBooks()
{
    // fill this array with 12 [BookInfo] class objects in this method and everything else works automatically
    books_recent = []    

    let i = 0;
    while (i < 12)
    {
        book = books_all[Math.floor(Math.random() * (books_all.length - 1))];
        if(books_recent.includes(book))
            continue;

        books_recent.push(book);
        i++;
    }
}

// Try download json data from server
async function DownloadJsonData(url) {
    try {
        const response = await fetch(url, {method: 'POST'});
        
        if (!response.ok) {
            throw new Error(`Failed to download ${url}. Status: ${response.status}`);
        }

        const jsonContent = await response.json();
        return jsonContent;
    } catch (error) {
        console.error(`Error during download and parsing: ${error.message}`);
        throw error;
    }
}

// Parse JSON response from server
function ParseJsonBooks(parsedData) {
    // const parsedData = JSON.parse(jsonString);
    const bookInfos = [];

    for (const entry of parsedData) {
        const bookInfo = new BookInfo();
        bookInfo.id = entry.id || 0;
        bookInfo.title = entry.title || "No Name";
        bookInfo.subtitle = entry.subtitle || "No subtitle";
        bookInfo.title_original = entry.title_original || "";
        bookInfo.subtitle_original = entry.subtitle_original || "";
        bookInfo.description = entry.description || "No description";
        bookInfo.pages = entry.pages || 0;
        bookInfo.year = entry.year || 1900;
        bookInfo.month = entry.month || 12;
        bookInfo.day = entry.day || 1;
        bookInfo.authors = entry.authors || ["First Author", "Second Author"];
        bookInfo.rating = entry.rating || 100;
        bookInfo.genres = entry.genres || ["genre 1", "genre 2"];
        bookInfo.imageUrl = entry.imageUrl || "";
        bookInfo.copies = entry.copies || 0;
        bookInfo.binding = entry.binding || "";
        bookInfo.isbn = entry.isbn || 0;
        bookInfo.location = entry.location || "Knowhere";
        bookInfo.publisher = entry.publisher || "Who knows";
        bookInfo.isRecommended = entry.isRecommended || false;
        bookInfo.isRecentRead = entry.isRecentRead || false;

        if(bookInfo.genres[0] == null) {
            bookInfo.genres = ["null"];
        }
        bookInfos.push(bookInfo);
    }

    return bookInfos;
}

// Generate url address for book - add ID parameter
function GetBookPageUrl(book)
{
    let bookPageUrl = "/book/";
    return bookPageUrl + book.id.toString();
}

// Generate URL address for author - add author name parameter
function GetAuthorPageUrl(author)
{    
    let bookPageUrl = "./author_page/index.html";

    let params = new URLSearchParams();
    params.append("author", author);

    return bookPageUrl + "?" + params.toString();
}


// Fill middle scroll book item with data
function SetMiddleScrollPageData(page, book)
{
    // Set book page url link and set parameters sent to the page
    let a = page.querySelector(".img-container").parentNode;
    a.href = GetBookPageUrl(book);

    // Set author url link
    // let author_a = page.querySelector(".name-text").parentNode;
    // author_a.href = GetAuthorPageUrl(book.authors[0]);

    let img = page.querySelector(".img-container > img");
    let genreText = page.querySelector(".genre-text");
    let descriptionText = page.querySelector(".description-text");
    let nameText = page.querySelector(".name-text");


    img.src = book.imageUrl;
    genreText.textContent = book.genres[0];
    descriptionText.textContent = book.description;
    nameText.textContent = book.title;

    genreText.parentNode.style.backgroundColor = GetGenreColor(book.genres[0]);
}


// Fill bottom scroll book item with data
function SetBottomScrollPageData(page, book)
{
    // Set book page url link and set parameters sent to the page
    let book_a = page.querySelector(".bot-scrl-name").parentNode;
    book_a.href = GetBookPageUrl(book);

    // Set author url link
    let author_a = page.querySelector(".bot-scrl-author").parentNode;
    author_a.href = GetAuthorPageUrl(book.authors[0]);


    let img = page.querySelector(".bot-scrl-img");
    let name = page.querySelector(".bot-scrl-name");
    let author = page.querySelector(".bot-scrl-author");
    let category = page.querySelector(".bot-scrl-genre");
    let opportunity = page.querySelector(".bot-scrl-opportunity");
    let status = page.querySelector(".bot-scrl-status");
    let action = page.querySelector(".action");

    selectedGenre = book.genres[0];

    // if genre filter applied - ensure and show related genre from this book    
    if (genres_dropdown_selected != "")
    {
        for (let i = 0; i < book.genres.length; i++)
        {
            if (book.genres[i].toLowerCase() == genres_dropdown_selected.toLowerCase())
            {
                selectedGenre = book.genres[i];
            }
        }
    }

    if (Math.random()*2 > 1)
    {
        status.textContent="Available"
    }

    action.setAttribute('data-id', book.id);
    img.src = book.imageUrl;
    name.textContent = book.title;
    author.textContent = book.authors[0];
    category.textContent = selectedGenre;
    category.parentNode.style.backgroundColor = GetGenreColor(selectedGenre);    
}


// VARIABLES
let enableMobileScaling = false;

// Book arrays
let books_recommended = [];
let books_recent = [];
let books_all = [];


// Recommended-Recent scroll
let hor_scrl_pages;
let hor_scrl_arrow_left;
let hor_scrl_arrow_right;
let hor_scrl_page_btn_fillers = [];

let hor_scrl_scrollWidth = 800;
let hor_scrl_scrollIndex = 0;
let hor_scrl_scrollIndexStart = 0;
let hor_scrl_scrollIndexEnd = 2;

// Recommended-Recent tabs
let hor_scrl_tabs = [];
let hor_scrl_tabs_books = [];

// Bottom scroll
let bot_scrl_line;
let bot_scrl_panel;
let bot_scrl_book;
let bot_scrl_content;
let bot_scrl_books = [];
let bot_scrl_books_data = [];
let bot_scrl_books_data_original = [];

// Genres dropdown
let genres_dropdown_btn;
let genres_root;
let genres_dropdown_shown;
let genres_dropdown_selected = "";

// We are getting genres for dropdown from current books.
// We can specify how many genres we take into account from each book
let genres_taken_from_book = 2;

// Searchbar
let searchbar_input;

document.addEventListener('DOMContentLoaded', async () =>
{
    await GetAllBooks();
    await GetRecentBooks();
    await GetRecommendedBooks();
    InitRecommendedRecentScroll();
    InitBookListScroll();
    InitGenresDropdown();
    InitSearchbar();

    SetTab(0);
    UpdateFillerIndex();

    CheckBotScrollPosition(true);
    CheckScaling();
});

function InitSearchbar()
{
    searchbar_input = document.querySelector(".search-input input");
    let crossBtn = document.querySelector(".search-cross-btn");
    searchbar_input.value = "";

    crossBtn.addEventListener("click", () => { searchbar_input.value = ""; ProcessSearch(); });

    searchbar_input.addEventListener("input", () =>
    {
        ProcessSearch();
    });

}

function ProcessSearch()
{

    let filteredBooks = []
    let filter = searchbar_input.value.toLowerCase();

    if (filter == "")
    {
        filteredBooks.push(...bot_scrl_books_data_original);
    }
    else
    {
        for (let i = 0; i < bot_scrl_books_data_original.length; i++)
        {
            let book = bot_scrl_books_data_original[i];

            let nameContains = book.title.toLowerCase().includes(filter);
            let originalNameContains = book.title_original.toLowerCase().includes(filter);
            let authorContains = false;

            for (let a = 0; a < book.authors.length; a++)
            {
                if (book.authors[a].toLowerCase().includes(filter))
                {
                    authorContains = true;
                }
            }

            if (nameContains || originalNameContains || authorContains)
            {
                filteredBooks.push(book);
            }
        }
    }

    bot_scrl_books_data = filteredBooks;
    ClearBotBooksList();
    CheckBotScrollPosition();
}

function InitGenresDropdown()
{
    genres_dropdown_btn = document.querySelector(".category-dropdown");
    genres_root = document.querySelector(".category-dropdown-content");
    let div = document.querySelector(".category-dropdown-content > div");

    let genres = []

    for (let i = 0; i < books_all.length; ++i)
    {
        let b = books_all[i];

        for (let g = 0; g < Math.min(genres_taken_from_book, b.genres.length); ++g)
        {
            if (!genres.includes(b.genres[g]))
            {
                genres.push(b.genres[g]);
            }
        }
    }

    for (let i = 0; i < genres.length; i++)
    {
        let genre = div.cloneNode(true);
        let text = genre.querySelector("div");

        text.textContent = genres[i].charAt(0).toUpperCase() + genres[i].slice(1);;
        genre.style.backgroundColor = GetGenreColor(genres[i]);

        genres_root.appendChild(genre);

        let selectedGenre = genres[i];

        genre.addEventListener("click", () => FilterBotBooksList(selectedGenre));
    }


    div.querySelector("div").textContent = "All genres";
    div.style.backgroundColor = "#708ecf";
    div.addEventListener("click", () => FilterBotBooksList("all"));

    genres_dropdown_shown = false;
    genres_dropdown_btn.addEventListener("click", () => ToggleDropdown());

    window.addEventListener("click", (event) =>
    {
        if (genres_dropdown_shown && !event.target.matches(".category-dropdown-content") && !event.target.matches(".category-dropdown") && !genres_root.contains(event.target) && !genres_dropdown_btn.contains(event.target))
        {
            ToggleDropdown(0);
        }
    });

    window.addEventListener("resize", () =>
    {
        ToggleDropdown(0);


        CheckScaling();
    });
}

function CheckScaling()
{
    if (!enableMobileScaling)
    {
        return;
    }

    let width = window.innerWidth;

    if (width <= 1200)
    {
        let factor = width / 780.0;
        SetPageScale(factor);
    }
    else
    {
        SetPageScale(1);
    }
}

function FilterBotBooksList(genre)
{
    bot_scrl_books_data = [];

    if (genre == "all")
    {
        genres_dropdown_selected = "";
        bot_scrl_books_data.push(...books_all);
    }
    else
    {
        genres_dropdown_selected = genre;

        for (let i = 0; i < books_all.length; i++)
        {
            let book = books_all[i];

            for (let g = 0; g < book.genres.length; g++)
            {
                if (genre.toLowerCase() == book.genres[g].toLowerCase())
                {
                    bot_scrl_books_data.push(book);
                }
            }
        }
    }

    let prevLength = bot_scrl_books.length;

    for (let i = prevLength - 1; i >= 0; i--)
    {
        bot_scrl_books[i].remove();
    }

    bot_scrl_books_data_original = [];
    bot_scrl_books_data_original.push(...bot_scrl_books_data);

    bot_scrl_books = [];
    ToggleDropdown();
    CheckBotScrollPosition();
}

function ClearBotBooksList()
{
    let prevLength = bot_scrl_books.length;

    for (let i = prevLength - 1; i >= 0; i--)
    {
        bot_scrl_books[i].remove();
    }

    bot_scrl_books = [];
}

function ToggleDropdown(status = -1)
{
    if (genres_dropdown_shown || status == 0)
    {
        genres_root.style.display = "none";
        genres_dropdown_shown = false;
    }
    else if (!genres_dropdown_shown || status == 1)
    {
        genres_root.style.display = "flex";
        
        let left = GetElementLeft(genres_dropdown_btn) + "px";
        genres_root.style.left = left;

        let top = GetElementTop(genres_dropdown_btn) + "px";
        genres_root.style.top = top;

        genres_root.style.width = genres_dropdown_btn.offsetWidth + "px";

        genres_dropdown_shown = true;
    }
}

function InitBookListScroll()
{
    bot_scrl_line = document.querySelector(".line-end");
    bot_scrl_panel = document.querySelector(".book-list-panel");
    bot_scrl_content = document.querySelector(".book-list");

    bot_scrl_books_data.push(...books_all);
    bot_scrl_books_data_original.push(...bot_scrl_books_data);

    window.addEventListener("scroll", () =>
    {
        CheckBotScrollPosition();
    });
}

function CheckBotScrollPosition(forcedAdd = false)
{
    const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
    // console.log(scrollTop + " " + scrollHeight + " " + clientHeight);

    // let lastBookEnd = bot_scrl_line.offsetTop + bot_scrl_panel.offsetTop;

    if (forcedAdd || (scrollHeight <= clientHeight + scrollTop))
    {
        // console.log(lastBookEnd);
        if (AddBotScrollBooks(10))
        {
            CheckBotScrollPosition();
        }
    }
}

function AddBotScrollBooks(count)
{
    if (bot_scrl_book == null)
    {
        bot_scrl_book = document.querySelector(".book-card-horizontal");
        bot_scrl_book.style.display = "none";
    }

    let bookDataIndex = bot_scrl_books.length;


    for (let i = 0; i < count; i++)
    {
        let nextBookIndex = bookDataIndex + i;

        if (nextBookIndex >= bot_scrl_books_data.length)
        {
            return false;
        }

        let page = bot_scrl_book.cloneNode(true);
        bot_scrl_books.push(page);
        bot_scrl_content.appendChild(page);


        page.style.display = "inline-flex";

        let bd = bot_scrl_books_data[nextBookIndex];

        SetBottomScrollPageData(page, bd);
    }

    return bot_scrl_books.length < bot_scrl_books_data.length;
}

function InitRecommendedRecentScroll()
{
    hor_scrl_pages = document.querySelector(".hor-scrl-pages");
    hor_scrl_arrow_left = document.getElementById("hor-scrl-arrow-left");
    hor_scrl_arrow_right = document.getElementById("hor-scrl-arrow-right");
    hor_scrl_btn_0 = document.getElementById("btn-0");;
    hor_scrl_btn_1 = document.getElementById("btn-1");;
    hor_scrl_btn_2 = document.getElementById("btn-2");;

    hor_scrl_page_btn_fillers = [document.getElementById("btn-0-filler"), document.getElementById("btn-1-filler"), document.getElementById("btn-2-filler")];

    hor_scrl_tabs.push(document.getElementById("tab-recommended"));
    hor_scrl_tabs.push(document.getElementById("tab-recent"));

    hor_scrl_tabs_books.push(books_recommended);
    hor_scrl_tabs_books.push(books_recent);

    hor_scrl_tabs[0].addEventListener("click", () =>
    {
        SetTab(0);
        UpdateFillerIndex();
    });

    // hor_scrl_tabs[1].addEventListener("click", () =>
    // {
    //     SetTab(1);
    //     UpdateFillerIndex();
    // });

    hor_scrl_arrow_left.addEventListener("click", () =>
    {
        hor_scrl_scrollIndex -= 1;
        hor_scrl_scrollIndex = Math.min(Math.max(hor_scrl_scrollIndex, hor_scrl_scrollIndexStart), hor_scrl_scrollIndexEnd);
        hor_scrl_pages.scrollLeft = hor_scrl_scrollWidth * hor_scrl_scrollIndex;
        UpdateFillerIndex();
    });

    hor_scrl_arrow_right.addEventListener("click", () =>
    {
        hor_scrl_scrollIndex += 1;
        hor_scrl_scrollIndex = Math.min(Math.max(hor_scrl_scrollIndex, hor_scrl_scrollIndexStart), hor_scrl_scrollIndexEnd);
        hor_scrl_pages.scrollLeft = hor_scrl_scrollWidth * hor_scrl_scrollIndex;
        UpdateFillerIndex();
    });

    hor_scrl_btn_0.addEventListener("click", () =>
    {
        hor_scrl_scrollIndex = 0;
        hor_scrl_pages.scrollLeft = hor_scrl_scrollWidth * hor_scrl_scrollIndex;
        UpdateFillerIndex();
    });

    hor_scrl_btn_1.addEventListener("click", () =>
    {
        hor_scrl_scrollIndex = 1;
        hor_scrl_pages.scrollLeft = hor_scrl_scrollWidth * hor_scrl_scrollIndex;
        UpdateFillerIndex();
    });

    hor_scrl_btn_2.addEventListener("click", () =>
    {
        hor_scrl_scrollIndex = 2;
        hor_scrl_pages.scrollLeft = hor_scrl_scrollWidth * hor_scrl_scrollIndex;
        UpdateFillerIndex();
    });
}

function UpdateFillerIndex()
{
    hor_scrl_page_btn_fillers[0].style.visibility = 'hidden';
    hor_scrl_page_btn_fillers[1].style.visibility = 'hidden';
    hor_scrl_page_btn_fillers[2].style.visibility = 'hidden';

    hor_scrl_page_btn_fillers[hor_scrl_scrollIndex].style.visibility = '';
}

function SetTab(tabIndex)
{
    hor_scrl_tabs[0].querySelector(".underline").style.backgroundColor = 'rgba(0, 0, 0, 0)';
    // hor_scrl_tabs[1].querySelector(".underline").style.backgroundColor = 'rgba(0, 0, 0, 0)';

    hor_scrl_tabs[tabIndex].querySelector(".underline").style.backgroundColor = '#0075ff';

    hor_scrl_scrollIndex = 0;

    let prevB = hor_scrl_pages.style.scrollBehavior;
    hor_scrl_pages.style.scrollBehavior = 'auto';
    hor_scrl_pages.scrollLeft = 0;
    hor_scrl_pages.style.scrollBehavior = prevB;


    SetTabBooks(hor_scrl_tabs_books[tabIndex]);
}

function SetTabBooks(books)
{
    let content = hor_scrl_pages.querySelector(".hor-scrl-content");
    let currentPages = content.querySelectorAll(".hor-scrl-page");
    let pagePrototype = currentPages[0];

    let pages = [];

    for (let i = 0; i < books.length; i++)
    {
        let page = pagePrototype.cloneNode(true);
        pages.push(page);
        content.appendChild(page);
    }

    let prevCount = currentPages.length;

    for (let i = prevCount - 1; i >= 0; i--)
    {
        currentPages[i].remove();
    }

    for (let i = 0; i < books.length; i++)
    {
        let page = pages[i];
        let book = books[i];

        SetMiddleScrollPageData(page, book);
    }
}

let id = 0;

function GetRandomBooks(bookCount, imgNameOffset, namePrefix, randomImages = false, includeGenreToName = false)
{
    const result = [];

    let genres = Object.keys(genresColors);
    genres.splice(15);

    for (let i = 0; i < bookCount; i++)
    {
        let book = new BookInfo();

        book.id = id;
        id++;
        book.authors = [];
        book.authors.push(namePrefix + " Author " + i);
        book.authors.push("Second Author " + i);


        book.description = namePrefix + " book description is a random text that was written to try to overflow book description is a random text that was written to try to overflow book description is a random text that was written to try to overflow book description is a random text that was written to try to overflow the container and test if ellipsis works here" + i;
        // book.pages = Math.round(Math.random() * 400 + 100);
        // book.year = Math.round(Math.random() * 1023 + 1000);
        // book.rating = Math.round(Math.random() * 100);

        if (randomImages)
        {
            book.imageUrl = "img/" + (Math.round(Math.random() * 25)) + ".jpg";
        }
        else
        {
            book.imageUrl = "img/" + ((i + imgNameOffset) % 26) + ".jpg";
        }

        book.genres = [];
        let randomGenre = genres[(i * 2) % genres.length];
        randomGenre = randomGenre.charAt(0).toUpperCase() + randomGenre.slice(1);
        let randomGenre2 = genres[(i + 1) % genres.length];
        randomGenre2 = randomGenre2.charAt(0).toUpperCase() + randomGenre2.slice(1);

        book.genres.push(randomGenre);
        book.genres.push(randomGenre2);

        if (includeGenreToName)
        {
            book.title = randomGenre + " Book " + i;
        }
        else
        {
            book.title = namePrefix + " Book " + i;
        }

        result.push(book);
    }

    return result;
}



function SetPageScale(scaleFactor)
{
    document.body.style.transform = `scale(${scaleFactor})`;
}

function GetElementLeft(element)
{
    const rect = element.getBoundingClientRect();
    return rect.left + window.scrollX;
}

function GetElementTop(element) {
    const rect = element.getBoundingClientRect();
    return rect.top + window.scrollY;
}

function GetGenreColor(genre)
{
    return genresColors[genre.toLowerCase()];
}

function toggleCart() {
    const cart = document.getElementById('cart');
    cart.classList.toggle('active');
}
// Funkcja do dodania książki do koszyka
function addToCart(bookId) {
    // Pobierz aktualny koszyk z LocalStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let bookInfo = books_all.find(book => book.id == bookId);
    // Dodaj książkę do koszyka
    const book = { id: bookId, name: bookInfo.title, author: bookInfo.authors[0] };
    console.log(book);
    cart.push(book);

    // Zapisz koszyk w LocalStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    document.getElementById("counter").textContent = cart.length;

    // Zaktualizuj widok koszyka
    updateCartView();
}

// Funkcja do aktualizacji widoku koszyka
function updateCartView() {
    const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = ''; // Wyczyszczenie listy

    cartItems.forEach(item => {
        const div = document.createElement('div');
        const removeButton = document.createElement('span');
        const li = document.createElement('li');
        li.textContent = `${item.name} by ${item.author}`;

        div.classList.add('cart-item');
        div.appendChild(li);
        removeButton.textContent = 'X';
        removeButton.classList.add('remove-btn');
        removeButton.addEventListener('click', () => {
            removeFromCart(item.id);
        });
        div.appendChild(removeButton);

        cartItemsContainer.appendChild(div);

    });
}


function removeFromCart(bookId) {
    // Pobierz aktualny koszyk z LocalStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    // Usuń książkę z koszyka
    cart = cart.filter(item => item.id != bookId);
    // Zapisz koszyk w LocalStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    // Zaktualizuj widok koszyka
    updateCartView();
}

// Inicjalizacja widoku koszyka na stronie
document.addEventListener('DOMContentLoaded', () => {
    //hide cart
    const cart = document.getElementById('cart');
    cart.classList.remove('active');


    updateCartView();
    document.getElementById("counter").textContent = JSON.parse(localStorage.getItem('cart')).length;

    // Dodanie książki do koszyka po kliknięciu przycisku "Book from 20.03"
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const bookId = button.getAttribute('data-book-id');
            const bookName = button.getAttribute('data-book-name');
            const bookAuthor = button.getAttribute('bot-scrl-author');
            console.log("=====", bookId, bookName, bookAuthor);
            addToCart(bookId);
        });
    });

});



// IF NEW GENRES WILL BE ADDED - ADD THEM HERE AND ASSIGN COLOR
const genresColors = {
    "fiction": "#9933FF",  // Light Purple
    "fantasy": "#3399FF",  // Light Blue
    "comedy": "#FFD633",  // Light Yellow
    "horror": "#FF5A5E",  // Light Red
    "history": "#6699FF",  // Sky Blue
    "science": "#6699FF",  // Sky Blue
    "mystery": "#FF6699",  // Lighter Pink
    "thriller": "#FF3333",  // Lighter Red
    "historical": "#6699FF",  // Sky Blue
    "humor": "#FFB300",  // Amber
    "romance": "#FFCCCC",  // Lightest Pink
    "poetry": "#FF66FF",  // Lighter Magenta
    "science fiction": "#6699FF",  // Sky Blue
    "dystopia": "#6699FF",  // Sky Blue
    "adventure": "#CCFFCC",  // Lightest Green
    "plays": "#CC9966",  // Lighter Brown
    "mythology": "#CC66FF",  // Light Purple
    "self help": "#FFD633",  // Light Yellow
    "psychology": "#FF66FF",  // Lighter Magenta
    "medieval": "#CC9966",  // Lighter Brown
    "novels": "#9933FF",  // Light Purple
    "gothic": "#9966CC",  // Light Purple
    "economics": "#6699FF",  // Sky Blue
    "crime": "#FF5050",  // Light Red
    "nonfiction": "#66CC66",  // Light Green
    "literary fiction": "#FFCC33",  // Yellow
    "business": "#6666FF",  // Blue
    "young adult": "#33CC33",  // Green
    "japan": "#FF8080",  // Light Salmon
    "finance": "#FF6633",  // Light Orange
    "world war ii": "#999999",  // Gray
    "literature": "#CC9900",  // Darker Yellow
    "classics": "#996633",  // Brown
    "biography memoir": "#FF99CC",  // Lighter Pink
    "paranormal": "#9933FF",  // Light Purple
    "feminism": "#FF99CC",  // Lighter Pink
    "realistic fiction": "#FFCC66",  // Light Orange
    "magical realism": "#CCFFCC",  // Lightest Green
    "high fantasy": "#993333",  // Darker Maroon
    "childrens": "#99CCFF",  // Light Blue
    "drama": "#FF3399",  // Lighter Pink
    "suspense": "#FF9966",  // Light Orange
    "biography": "#FF9966",  // Light Orange
    "picture books": "#CCCCFF",  // Lighter Purple
    "animals": "#CC9966",  // Lighter Brown
    "vampires": "#9933FF",  // Light Purple
    "mystery thriller": "#FF6699",  // Lighter Pink
    "time travel": "#CCFF66",  // Light Yellow-Green
    "spirituality": "#CCFF99",  // Lightest Green
    "read for school": "#66CCFF",  // Lighter Grayish Blue
    "nature": "#66CC66",  // Lighter Dark Green
    "book club": "#FFCCFF",  // Lighter Purple
    "adult fiction": "#FF3399",  // Lighter Pink
    "post apocalyptic": "#999999",  // Lighter Gray
    "politics": "#FF5050",  // Light Red
    "contemporary": "#FF8080",  // Light Salmon
    "tragedy": "#FF5050",  // Light Red
    "coming of age": "#FFCC66",  // Light Orange
    "epic fantasy": "#993333",  // Darker Maroon
    "science fiction fantasy": "#6699FF",  // Sky Blue
    "travel": "#FFD633",  // Light Yellow
    "war": "#999999",  // Lighter Gray
    "spanish literature": "#FF5A5E",  // Light Red
    "africa": "#CC9966",  // Lighter Brown
    "philosophy": "#CC9900",  // Darker Yellow
    "inspirational": "#FFCC99",  // Lightest Peach
    "holocaust": "#999999",  // Lighter Gray
    "mental health": "#FFD633",  // Light Yellow
    "france": "#6699FF",  // Sky Blue
    "autobiography": "#FF9966",  // Light Orange
    "sociology": "#6699FF",  // Sky Blue
    "adult": "#FF3399",  // Lighter Pink
    "theatre": "#FF6699",  // Lighter Pink
    "magic": "#9933FF",  // Light Purple
    "memoir": "#FF9966",  // Light Orange
    "urban fantasy": "#3399FF",  // Light Blue
    "contemporary romance": "#FF8080",  // Light Salmon
    "christian": "#CC9966",  // Lighter Brown
    "legal thriller": "#FF5050",  // Light Red
    "christian fiction": "#CC9966",  // Lighter Brown
    "american": "#6699FF",  // Sky Blue
    "french literature": "#6699FF",  // Sky Blue
    "historical fiction": "#6699FF",  // Sky Blue
    "space opera": "#6699FF",  // Sky Blue
    "school": "#CC9966",  // Lighter Brown
    "chick lit": "#FFCCCC",  // Lightest Pink
    "middle grade": "#99CCFF",  // Light Blue
    "19th century": "#6699FF",  // Sky Blue
    "audiobook": "#FF66FF",  // Lighter Magenta
  };

