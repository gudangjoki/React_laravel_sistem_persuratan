import { ReactNode, createContext, useState } from "react";

interface Props {
  children?: ReactNode;
}

interface CalendarContextType {
  monthToString: string[];
  getYear: number;
  getMonth: number;
  setYear: (year: number) => void;
  setMonth: (month: number) => void;
  leftComponentClicked: boolean;
  setLeftComponentClicked: (clicked: boolean) => void;
  rightComponentClicked: boolean;
  setRightComponentClicked: (clicked: boolean) => void;
}

export const CalendarContext = createContext<CalendarContextType>({
  monthToString: [],
  getYear: 0,
  getMonth: 0,
  setYear: () => {},
  setMonth: () => {},
  leftComponentClicked: false,
  setLeftComponentClicked: () => {},
  rightComponentClicked: false,
  setRightComponentClicked: () => {}
});

export const CalendarProvider = ({ children }: Props) => {
  const [monthToString] = useState([
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ]);

  const [getYear, setYear] = useState<number>(2024);
  const [getMonth, setMonth] = useState<number>(4);

  const [leftComponentClicked, setLeftComponentClicked] = useState<boolean>(false)
  const [rightComponentClicked, setRightComponentClicked] = useState<boolean>(false)

  const calendarContextValue = {
    monthToString,
    getYear,
    getMonth,
    setYear,
    setMonth,
    leftComponentClicked,
    setLeftComponentClicked,
    rightComponentClicked,
    setRightComponentClicked
  };

  return (
    <CalendarContext.Provider value={calendarContextValue}>
      {children}
    </CalendarContext.Provider>
  );
};
