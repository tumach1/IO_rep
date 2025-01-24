let book;

document.addEventListener('DOMContentLoaded', async () =>
{
    await GetBook();
    InitBookPage();
});

async function GetBook()
{
    // fill this array with ANY number of [BookInfo] class objects in this method and everything else works automatically

    try
    {
        // try load data from local server
        let data = await DownloadJsonData("/book/" + idBook);
        book = ParseJsonBook(data);
    } catch (error)
    {
        // fake data
        books_all = GetRandomBooks(100, 0, "", false, true);
    }
}


function FillBookPage(book)
{
    let img = document.querySelector(".info-image > img");
    img.src = book.imageUrl;

    let title = document.querySelector(".info-data .title > span");
    title.textContent = book.title;

    let subtitle = document.querySelector(".info-data .subtitle>span");
    subtitle.textContent = book.subtitle;

    let pages = document.querySelector(".info-data .pages .text");
    pages.textContent = book.pages;

    let copies = document.querySelector(".info-data .copies .text");
    copies.textContent = book.copies;

    let original_title = document.querySelector(".info-data .original-title .text");
    original_title.textContent = book.title_original;

    let original_subtitle = document.querySelector(".info-data .original-subtitle .text");
    original_subtitle.textContent = book.subtitle_original;

    let binding = document.querySelector(".info-data .binding .text");
    binding.textContent = book.binding;

    let ISBN = document.querySelector(".info-data .ISBN .text");
    ISBN.textContent = book.isbn;

    let location = document.querySelector(".info-data .location .text");
    location.textContent = book.location;

    let description = document.querySelector(".description-text");
    description.textContent = book.description;

    // Set genres
    genres_root = document.querySelector(".genres");
    let genre_span = document.querySelector(".genres > .genre-bg");

    for (let i = 0; i < book.genres.length; i++)
    {
        let genre = genre_span.cloneNode(true);
        let text = genre.querySelector(".genre-text");

        text.textContent = book.genres[i].charAt(0).toUpperCase() + book.genres[i].slice(1);;
        genre.style.backgroundColor = GetGenreColor(book.genres[i]);

        genres_root.appendChild(genre);
    }

    genre_span.remove();
    
    // Set authors
    authors_root = document.querySelector(".author");
    let author_span = document.querySelector(".author > a");

    for (let i = 0; i < book.authors.length; i++)
    {
        let author = author_span.cloneNode(true);
        let author_text = author.querySelector(".text");

        author.href = GetAuthorPageUrl(book.authors[i])
        author_text.textContent = book.authors[i];
        authors_root.appendChild(author);
    }

    author_span.remove();

    
}

function GetAuthorPageUrl(author)
{    
    let bookPageUrl = "../author_page/index.html";

    let params = new URLSearchParams();
    params.append("author", author);

    return bookPageUrl + "?" + params.toString();
}


function InitBookPage()
{
    console.log(book);
    // Fill page with data
    FillBookPage(book);
}





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

function ParseJsonBook(parsedData) {
    // const parsedData = JSON.parse(jsonString);

    const bookInfo = new BookInfo();
    bookInfo.id = parsedData.id || 0;
    bookInfo.title = parsedData.title || "No Name";
    bookInfo.subtitle = parsedData.subtitle || "No subtitle";
    bookInfo.title_original = parsedData.title_original || "";
    bookInfo.subtitle_original = parsedData.subtitle_original || "";
    bookInfo.description = parsedData.description || "No description";
    bookInfo.pages = parsedData.pages || 123;
    bookInfo.year = parsedData.year || 1900;
    bookInfo.month = parsedData.month || 12;
    bookInfo.day = parsedData.day || 1;
    bookInfo.authors = parsedData.authors || ["First Author", "Second Author"];
    bookInfo.rating = parsedData.rating || 100;
    bookInfo.genres = parsedData.genres || ["genre 1", "genre 2"];
    bookInfo.imageUrl = parsedData.imageUrl || "placeholder-portrait.png";
    bookInfo.copies = parsedData.copies || 123123;
    bookInfo.binding = parsedData.binding || "";
    bookInfo.isbn = parsedData.isbn || "123123123";
    bookInfo.location = parsedData.location || "Knowhere";
    bookInfo.publisher = parsedData.publisher || "Who knows";
    bookInfo.isRecommended = parsedData.isRecommended || false;
    bookInfo.isRecentRead = parsedData.isRecentRead || false;

    if(bookInfo.genres[0] == null) {
        bookInfo.genres = ["null"];
    }

    return bookInfo;
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
        book.pages = (i * 273 + 5453) % 935 + (i * 77 + 5453) % 123 + (i * 7 + 5453) % 17;
        book.year = 1800 + (i * 300) % 200 + (i * 20) % 100 + (i * 7) % 10;
        book.rating = Math.round(Math.random() * 100);

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

        book.title_original = "Original " + book.title;
        book.subtitle = "You are now reading a subtitle for randomly generated book";
        book.subtitle_original = "This is original subtitle for a book";
        book.copies = (i * 2346) % 134556 + (i * 23) % 542 + (i * 7) % 89;

        book.isbn = (i * 345353).toString();
        book.binding = "What is this thing?";
        book.location = "Krakow, Poland";

        result.push(book);
    }

    return result;
}


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
    isbn = "0";
    location = "Knowhere";    
    publisher = "Who knows"

    isRecommended = false;
    isRecentRead = false;
}



function GetGenreColor(genre)
{
    return genresColors[genre.toLowerCase()];
}

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
