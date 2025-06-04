//Detect what image to show
function esrb_rating(){
  for (let i = 0; i < 7; i++) {
    const partialName = "ESRB_2013"; // The partial name to search for
    const filenames = ["ESRB_2013_Adults_Only_18+.svg.png", "ESRB_2013_Early_Childhood.svg.png", "ESRB_2013_Everyone.svg.png", "ESRB_2013_Everyone_10+.svg.png"];
    // Create a regular expression for case-insensitive matching
    const regex = new RegExp(partialName, 'i'); // 'i' for case-insensitive
    const matchedFiles = filenames.filter(file => regex.test(file));
    console.log(matchedFiles); // ["fileTest1.txt", "TestFile2.txt", "TEST123.png"]
  }
}
esrb_rating();
