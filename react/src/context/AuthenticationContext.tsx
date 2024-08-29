import { ReactNode, createContext, useState } from "react";

export interface User {
  email: string;
  password: string;
  fname: string;
  lname: string;
}

interface Props {
  children?: ReactNode;
}

interface AuthenticationContextType {
  user: User | null;
  setUser: (user: User | null | ((prevUser: User | null) => User)) => void;
  url: string;
  setUrl: (url: string) => void;
}

export const AuthenticationContext = createContext<AuthenticationContextType>({
  user: null,
  setUser: () => {},
  url: "",
  setUrl: () => {},
});

export const AuthenticationProvider = ({ children }: Props) => {
  const [user, setUser] = useState<User | null>(null);
  // const [url, setUrl] = useState<string>("http://127.0.0.1:5000");
  const [url, setUrl] = useState<string>("https://eagerly-fine-husky.ngrok-free.app");
  // const [url, setUrl] = useState<string>("https://cd00-182-253-129-86.ngrok-free.app");

  const authenticationContextValue = {
    user,
    setUser,
    url,
    setUrl,
  };

  return (
    <AuthenticationContext.Provider value={authenticationContextValue}>
      {children}
    </AuthenticationContext.Provider>
  );
};

export default AuthenticationContext;
