import { useContext } from "react";
import { CalendarContext } from "../context/CalendarContext";

const useDateDay = () => {
  return useContext(CalendarContext);
};

export default useDateDay;
