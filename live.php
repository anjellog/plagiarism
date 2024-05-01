<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Assignment Plagiarism Checker</title>
  <link rel="icon" href="logo.ico" type="image/x-icon" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
  <div class="container mx-auto mt-10 px-5">
    <div class="max-w-2xl mx-auto bg-white p-8 border rounded-lg shadow-lg">
      <h1 class="text-2xl font-bold mb-4">Online Assignment Plagiarism Checker</h1>
      <form id="plagiarismForm" action="" method="post">
        <textarea name="text" id="text" rows="5"
          class="w-full p-2 mb-4 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
          placeholder="Enter text here..."></textarea>
        <button type="button" id="submitBtn"
          class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded-md">
          <svg class="h-5 w-5 text-white mr-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_429_11249)">
              <path d="M20 7.00018L10 17.0002L5 12.0002" stroke="white" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round" />
            </g>
            <defs>
              <clipPath id="clip0_429_11249">
                <rect width="24" height="24" fill="white" />
              </clipPath>
            </defs>
          </svg>
          Check Plagiarism
        </button>

        <div id="loader" class="hidden">
          <img src="https://loading.io/assets/mod/spinner/spinner/lg.gif" alt="Loading..." class="mx-auto mt-4">
        </div>
      </form>
      <div id="result" class="mt-4"></div>
    </div>
  </div>

  <script>
    document.getElementById("submitBtn").addEventListener("click", function () {
      const text = document.getElementById("text").value;
      const loader = document.getElementById("loader");
      loader.classList.remove('hidden');

      const data = JSON.stringify({
        text: text,
        language: 'en',
        includeCitations: false,
        scrapeSources: false
      });

      const xhr = new XMLHttpRequest();
      xhr.withCredentials = true;

      xhr.addEventListener('readystatechange', function () {
        if (this.readyState === this.DONE) {
          const response = JSON.parse(this.responseText);
          const percentPlagiarism = response.percentPlagiarism;
          const sources = response.sources;

          const resultDiv = document.getElementById("result");

          loader.classList.add('hidden');

          resultDiv.innerHTML = "";

          if (percentPlagiarism > 0) {
            let color = '';
            if (percentPlagiarism < 25) {
              color = 'bg-green-500';
            } else if (percentPlagiarism >= 25 && percentPlagiarism < 50) {
              color = 'bg-yellow-500';
            } else {
              color = 'bg-red-500';
            }

            resultDiv.innerHTML = `<div class="mb-2"><p class="text-lg font-semibold mb-2">Plagiarism Detected (${sources.length} match${sources.length > 1 ? 'es' : ''}):</p><div class="w-full bg-gray-200 rounded-lg overflow-hidden"><div class="${color} text-xs leading-none py-1 text-center text-white" style="width:${percentPlagiarism}%">${percentPlagiarism}% Plagiarism</div></div></div>`;
            sources.forEach(source => {
              const url = source.url;
              const title = source.title;

              const card = document.createElement("div");
              card.className = "bg-gray-100 p-4 border rounded-lg shadow mb-1";

              const titleElement = document.createElement("p");
              titleElement.className = "text-blue-500 font-semibold";
              const link = document.createElement("a");
              link.href = url;
              link.target = "_blank";
              link.textContent = title;
              titleElement.appendChild(link);
              card.appendChild(titleElement);

              const MatchedElement = document.createElement("p");
              MatchedElement.textContent = `Matched Text: `;
              MatchedElement.className = "ml-0 md:ml-4";
              card.appendChild(MatchedElement);

              source.matches.forEach(match => {
                const matchedText = document.createElement("p");
                matchedText.textContent = `${match.matchText}`;
                matchedText.className = "ml-4 md:ml-8";
                card.appendChild(matchedText);
              });

              resultDiv.appendChild(card);
            });
          } else {
            resultDiv.innerHTML = "<p class='text-green-500 font-semibold'>No plagiarism detected!</p>";
          }
        }
      });

      xhr.open('POST', 'https://plagiarism-checker-and-auto-citation-generator-multi-lingual.p.rapidapi.com/plagiarism');
      xhr.setRequestHeader('content-type', 'application/json');
      xhr.setRequestHeader('X-RapidAPI-Key', '7416d70546mshc90ad78632c3e3ep1a9707jsn8fbaed12fd1c');
      xhr.setRequestHeader('X-RapidAPI-Host', 'plagiarism-checker-and-auto-citation-generator-multi-lingual.p.rapidapi.com');

      xhr.send(data);
    });
  </script>
</body>

</html>
