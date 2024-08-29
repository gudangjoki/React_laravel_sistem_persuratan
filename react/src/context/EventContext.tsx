// eventContext.js
import { ReactNode, createContext, useEffect, useState } from "react";

interface Props {
  children?: ReactNode;
}

export const EventContext = createContext({});

export const EventProvider = ({ children }: Props) => {
  const [location, setLocation] = useState("");
  const [detailEvent, setDetailEvent] = useState({
    time: 0,
    distance: 0
  })
  const [mark, setMark] = useState({
    srcPoint: null,
    destPoint: null
  });
  // const [destPoint, SetDestPoint] = useState({});

  const eventContextValue = {
    location,
    setLocation,
    mark,
    setMark,
    detailEvent,
    setDetailEvent
  };

  useEffect(() => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition((position) => {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        setDetailEvent(data => ({
          ...data,
          srcPoint: {
            latitude,
            longitude
          }
        }))
      });
    } else {
      console.log("Geolocation is not supported by this browser.");
    }
  }, [])

  useEffect(() => {
    console.log(location);
  }, [location]);

  useEffect(() => {
    console.log(mark);
  }, [mark]);

  useEffect(() => {
    console.log(detailEvent);
  }, [detailEvent]);

  return (
    <EventContext.Provider value={eventContextValue}>
      {children}
    </EventContext.Provider>
  );
};
