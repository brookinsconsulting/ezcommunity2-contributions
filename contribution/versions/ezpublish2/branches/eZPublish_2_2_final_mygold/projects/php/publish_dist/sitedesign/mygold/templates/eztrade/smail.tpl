\documentclass[a4paper]{article}
\usepackage{graphics}
\usepackage{color}
\usepackage[T1]{fontenc}
\topmargin = -70pt
\oddsidemargin = 40pt
\textwidth = 500pt
\textheight = 800pt
\begin{document}
\begin{tabular}{l}
\scriptsize{\textsf{ }} \\
\end{tabular}


\definecolor{LRed}{rgb}{0.9,0.4,0.4} 
\definecolor{LGray}{gray}{0.8} 

 \begin{tabular*}{0.2\textwidth}{p{0.2\textwidth}}
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
\\
 \end{tabular*}





\begin{tabular}[c]{llr}
 & & 
	\begin{huge}
        \textsf{\bf{{voucher_value}\em}} 
	\end{huge}
\vspace*{4pt}
\\
 & & 

	\begin{small}

	  cd789sc78sd78gh78sdc
	\end{small}
\vspace*{7pt}
  \\        

% Empty lines
 &  & \\
 &  & \\

% To and from
 \\
 \begin{tabular*}{0.3\textwidth}{p{0.3\textwidth}}
\begin{center}
{to_name}\\
\end{center}
 \end{tabular*}
 & &
 \begin{tabular*}{0.2\textwidth}{p{0.2\textwidth}}
\begin{center}
{from_name}\\
\end{center}
 \end{tabular*}
  \\

% Empty line
 &  & \\
 &  & \\
 &  & \\

% Big gray box
\multicolumn{3}{c}{ 
 \begin{tabular*}{0.6\textwidth}{p{0.6\textwidth}}
Hello {to_name},

{description}
\\
{from_name}
\\
\\
 \end{tabular*}
  }
\\

% Empty line
 &  & \\

\end{tabular}


\end{document}
