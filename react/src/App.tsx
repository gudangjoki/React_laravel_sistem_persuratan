import { ChangeEvent, MouseEvent, useEffect, useState } from "react";
import "./App.css";

function App() {
  const [count, setCount] = useState("");
  const [username, setUsername] = useState("");

  // useEffect(() => {


  //   // Clean up the EventSource connection
  //   // return () => {
  //   //   evtSrc.close();
  //   // };
  // }, []);

  const inputChange = (e: ChangeEvent<HTMLInputElement>) => {
    setUsername(e.target.value);
  }

  useEffect(() => {
    console.log(username)
  }, [username])

  const click = (e: MouseEvent<HTMLButtonElement>) => {
    e.preventDefault()
    const evtSrc = new EventSource(`http://localhost:8005/events?username=${username}`);
    evtSrc.onmessage = function (event) {
      const data = event.data;
      if (data === "end") {
        evtSrc.close();
      } else {
        console.log(data);
        setCount(data);
      }
    };
  }

  return (
    
    <div>
      <div>
        <form>
        <input type="text" onChange={inputChange} name="username" />
        <button type="submit" onClick={click}>Submit</button>
        </form>
      </div>
      <h1>Count: {count}</h1>
    </div>
  );
}

export default App;
