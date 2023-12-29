export default async function readUserQuestions(user_id) {
  const response = await fetch("/server/api/Questions/readUserQuestions.php", {
    method: "POST",
    body: JSON.stringify({
      user_id: user_id,
    }),
    headers: {
      Accept: "application/json",
      "Content-type": "application/json",
    },
  });
  const questions = await response.json();

  return questions;
}
