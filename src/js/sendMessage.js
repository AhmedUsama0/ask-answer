export default async function sendMessage(sender, receiver, message) {
  if (message.trim()) {
    await fetch("/server/api/Messages/createMessage.php", {
      method: "POST",
      body: JSON.stringify({
        message: message.trim(),
        sender: sender,
        receiver: receiver,
      }),
      headers: {
        "Content-type": "application/json",
      },
    });
  }
}
