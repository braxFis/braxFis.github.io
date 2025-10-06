function GeneratorReviewText() {
  fetch("https://api.openai.com/v1/responses", {
    model: "gpt-4.1",
    input: "Write a review".review_title
  })
    .then(r => data.json/*...Come back here for seconds...*/);
}

function GeneratorReviewAudio(){/**/}
function GeneratorImage(){
  /*...Repeat again...*/
}
