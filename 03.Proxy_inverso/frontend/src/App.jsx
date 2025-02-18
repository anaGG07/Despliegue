import { useEffect, useState } from "react";

function App() {
  const [message, setMessage] = useState("");

  useEffect(() => {
    fetch(`${import.meta.env.VITE_API_URL}/api/amg`)
      .then((res) => res.json())
      .then((data) => setMessage(data.message));
  }, []);

  return (
    <div>
      <h1>Frontend en React de Ana María García García</h1>
      <p>
        Esta aplicación se conecta al backend de Symfony pidiéndole una
        respuesta
      </p>
      <p>Respuesta del Backend: {message || "Esperando respuesta..."}</p>
    </div>
  );
}

export default App;
