export default async function destroy(question_id) {
  await fetch("/server/api/Questions/deleteQuestion.php", {
    method: "POST",
    body: JSON.stringify({ question_id: question_id }),
    headers: {
      "Content-Type": "application/json",
    },
  });
}
